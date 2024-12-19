<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  /**
   * Show the form for creating a new resource.
   */
  public function actionLogin(Request $request)
  {
    $auth = Auth::guard();
    if ($auth->check()) {
      return redirect('/mypage');
    }

    if ($request->isMethod('post')) {
      $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
      ]);

      if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        return redirect()->intended('/mypage');
      }

      return back()->withErrors('メールアドレスまたはパスワードが正しくありません。')->withInput();
    }

    return view('frontend.login.index', ['title' => 'ログイン']);
  }

  public function actionForgotPassword(Request $request)
  {
    if ($request->isMethod('post')) {
      $validator = Validator::make($request->post(), [
        'email' => ['required', 'email'],
        'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
      ]);

      // バリデーション
      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }

      $input = $validator->validated();
      $user = DB::table('users')
        ->where('email', '=', $input['email'])
        ->where('tel', '=', $input['phone'])
        ->where('deleted_at', '=', null)
        ->first();

      // メールアドレスに該当するユーザーが見つからない、またはシステム用アカウントはアクセスできないように
      if (!$user) {
        return redirect()->back()->withErrors('一致するユーザは見つかりませんでした。')->withInput();
      }

      try {
        DB::beginTransaction();

        $token = Str::random(64);
        $nowInJapan = Carbon::now('Asia/Tokyo')->toDateTimeString();

        // リセットトークンを保存
        DB::table('password_resets')->insert([
          'email' => $input['email'],
          'token' => $token,
          'created_at' => $nowInJapan,
        ]);

        $user['url'] = url('/') . '/reset_password/?token=' . $token;

        // パスワードリセットURLをメールで送信
        EmailService::user($user, 2);

        DB::commit();
      } catch (Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());

        return redirect()->back()->withErrors($e->getMessage());
      }

      return redirect('forgot_password/complete')->with('email', $user['email']);
    }

    return view('frontend.password.forgot_password', ['title' => 'パスワードをお忘れお方']);
  }

  public function getForgotPasswordComplete()
  {
    if (session('email')) {
      return view('frontend.password.forgot_password_complete', ['title' => '再設定用メールを送信しました']);
    }

    return redirect('login');
  }

  /**
   * パスワード忘れでパスワード再設定を行う.
   */
  public function actionResetPassword(Request $request)
  {
    session(['token' => $request->input('token')]);
    if ($request->isMethod('post')) {
      $validator = Validator::make($request->post(), [
        'password' => ['required', 'confirmed', 'min:6', 'max:32', 'regex:/^(?=.*[0-9])(?=.*[a-zA-Z])[0-9a-zA-Z]+$/'],
        'verify_token' => ['required'],
      ]);

      // バリデーション
      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }

      $input = $validator->validated();
      $user = DB::table('users')
        ->select(['users.id', 'users.email', 'users.name'])
        ->join('password_resets', 'password_resets.email', '=', 'users.email')
        ->where('password_resets.token', '=', $input['verify_token'])
        ->where('password_resets.created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 minutes')))
        ->where('users.deleted_at', '=', null)
        ->first();

      if (!$user) {
        return redirect()->back()->withErrors('一致するユーザは見つかりませんでした。');
      }

      try {
        DB::beginTransaction();

        // パスワード変更
        DB::table('users')
          ->where('id', '=', $user['id'])
          ->update([
            'password' => Hash::make($input['password']),
          ]);

        // リセットトークンを削除
        DB::table('password_resets')
          ->where(['email' => $user['email']])
          ->delete();

        // ユーザーに通知メール送信
        EmailService::user($user, 3);

        DB::commit();
      } catch (Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());

        return redirect()->back()->withErrors($e->getMessage());
      }

      return redirect('reset_password/complete');
    }

    return view('frontend.password.reset_password', ['title' => 'パスワードを再設定する'])->with(['token' => session('token')]);
  }

  public function getResetPasswordComplete()
  {
    return view('frontend.password.reset_password_complete', ['title' => 'パスワード再設定完了']);
  }

  public function getLogout()
  {
    Auth::logout();

    return redirect('login');
  }
}
