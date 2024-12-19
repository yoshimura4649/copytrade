<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function actionRegister(Request $request)
  {
    if ($request->isMethod('post')) {
      $validator = $this->validatorRegister($request);

      if ($validator->fails()) {
        return redirect('register')->withErrors($validator)->withInput();
      }

      try {
        DB::beginTransaction();

        $user = DB::table('users')->find(
          DB::table('users')->insertGetId([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'tel' => $request['tel'],
          ])
        );

        EmailService::user($user, 1);

        DB::commit();

        return redirect('register/complete');
      } catch (Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());

        return redirect('register')->withErrors($e->getMessage())->withInput();
      }
    }

    return view('frontend.register.index', ['title' => 'ユーザー登録']);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function postRegisterConfirm(Request $request)
  {
    $validator = $this->validatorRegister($request);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    return view('frontend.register.confirm', ['title' => '登録内容確認', 'response' => $request]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function getRegisterComplete()
  {
    return view('frontend.register.complete', ['title' => 'ユーザー登録完了']);
  }

  public function validatorRegister($request)
  {
    $validator = Validator::make($request->post(), [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'tel' => 'required|regex:/^\+?[0-9]{10,15}$/',
      'password' => 'required|regex:/^(?=.*[0-9])(?=.*[a-zA-Z])[0-9a-zA-Z]+$/|min:6|max:32|confirmed',
    ])->setAttributeNames([
      'email' => '登録メールアドレス',
      'name' => '氏名',
      'tel' => '連絡先電話番号',
      'password' => 'パスワード',
      'password_confirmation' => 'パスワード（確認用）',
    ]);

    return $validator;
  }
}
