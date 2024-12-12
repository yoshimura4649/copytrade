<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Myemail;
use Exception;

class GuardController extends BaseController
{
  /**
   * CORE-API-01 ログイン・トークン取得API
   */
  public function postLogin(Request $request)
  {
    $validator = Validator::make($request->post(), [
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string', 'min:8', 'max:32'],
    ]);

    // バリデーション
    if ($validator->fails()) {
      return $this->responseError(400);
    }

    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      return $this->responseError(401);
    }

    $user = User::where('email', $request['email'])->firstOrFail();

    if (!$user) {
      return $this->responseError(404);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->responseSuccess([
      'user_id' => $user['id'],
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }

  /**
   * CORE-API-02 パスワード変更
   */
  public function postChangePassword(Request $request)
  {
    $validator = Validator::make($request->post(), [
      'old_password' => ['required', 'string' ,'min:8', 'max:32'],
      'new_password' => ['required', 'string', 'min:8', 'max:32'],
    ]);

    // バリデーション
    if ($validator->fails()) {
      return $this->responseError(400);
    }

    $input = $validator->validated();
    $user = $request->user();

    // 現在のパスワードをチェック
    if ($user['password'] && (empty($input['old_password']) || !Hash::check($input['old_password'], $user['password']))) {
      return $this->responseError(400);
    }

    // パスワード変更
    $user->password = Hash::make($input['new_password']);
    $user->save();

    $request->user()->tokens()->delete();

    // 成功した時
    return $this->responseSuccess();
  }

  /**
   * CORE-API-03 ログアウト
   */
  public function getLogout(Request $request)
  {
    // トークンを無効にする
    $request->user()->tokens()->delete();

    // 成功した時
    return $this->responseSuccess();
  }

  /**
   * CORE-API-04 パスワード再設定URL発行
   * メールアドレスでログインする時、パスワード忘れの場合、このAPIの経由で、メールでパスワード再設定APIが送られる。
   */
  public function postForgotPassword(Request $request)
  {
    // ログインしている状態でエラー
    if ($request->user()) {
      return $this->responseError(403);
    }

    $validator = Validator::make($request->post(), [
      'email' => ['required', 'string', 'email']
    ]);

    // バリデーション
    if ($validator->fails()) {
      return $this->responseError(400);
    }

    $input = $validator->validated();
    $user = DB::table('users')
      ->where('email', $input['email'])
      ->first();

    // メールアドレスに該当するユーザーが見つからないように
    if (!$user) {
      return $this->responseError(404);
    }

    try {
      DB::beginTransaction();

      $token = Str::random(64);

      // リセットトークンを保存
      DB::table('password_resets')->insert([
        'email' => $input['email'],
        'token' => $token,
      ]);

      $user['url'] = url('/') . "/reset_password/?token=" . $token;

      // パスワードリセットURLをメールで送信
      Myemail::user($user, 2);

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());

      return $this->responseError(500);
    }

    // 成功した時
    return $this->responseSuccess();
  }

  /**
   * CORE-API-05 パスワード忘れでパスワード再設定を行う
   *
   * パスワード忘れでCORE-API-04をコールした後、システムからリセットパスワードリンクを送る。
   * 本APIはパスワードを再設定する時利用される
   */
  public function postResetPassword(Request $request)
  {
    $validator = Validator::make($request->post(), [
      'new_password' => ['required', 'min:8', 'max:32'],
      'verify_token' => ['required'],
    ]);

    // バリデーション
    if ($validator->fails()) {
      return $this->responseError(400);
    }

    $input = $validator->validated();
    $user = DB::table('users')
      ->select(['users.id', 'users.email', 'users.name'])
      ->join('password_resets', 'password_resets.email', '=', 'users.email')
      ->where('password_resets.token', '=', $input['verify_token'])
      ->where('password_resets.created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 minutes')))
      ->first();

    // verify_tokenが無効、又は有効期限が切れている、またはシステム用アカウントはアクセスできないように
    if (!$user) {
      return $this->responseError(401);
    }

    try {
      DB::beginTransaction();

      // パスワード変更
      DB::table('users')
        ->where('id', '=', $user['id'])
        ->update([
          'password' => Hash::make($input['new_password']),
        ]);

      // リセットトークンを削除
      DB::table('password_resets')
        ->where(['email' => $user['email']])
        ->delete();

      // ユーザーに通知メール送信
      Myemail::user($user, 3);

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());

      return $this->responseError(500);
    }

    // 成功した時
    return $this->responseSuccess();
  }

  /**
   * CORE-API-06 ユーザー登録API
   *
   */
  public function postRegister(Request $request)
  {
    $validator = Validator::make($request->post(), [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'unique:users'],
      'password' => ['required', 'string', 'min:8', 'max:32']
    ]);

    // バリデーション
    if ($validator->fails()) {
      return $this->responseError(400);
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->responseSuccess([
      'user_id' => $user['id'],
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }
}
