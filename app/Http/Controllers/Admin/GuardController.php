<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Session;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class GuardController extends Controller
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  // use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = 'admin/guard/login';

  public function getIndex(Request $request)
  {
    $this->actionLogin($request);
  }

  public function actionLogin(Request $request)
  {
    $auth = Auth::guard('admin');
    $remember = ($request->input('remember')) ? true : false;

    if ($auth->check()) {
      return redirect($request->query('ref', 'admin/standard/home'));
    }

    // ログインフォームが投稿された場合
    if ($request->isMethod('post')) {
      if ($auth->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember)) {
        $request->session()->regenerate();

        return redirect($request->query('ref', 'admin/standard/home'));
      }
    }

    return view('admin.guard.login', ['title' => 'ログイン']);
  }

  /**
   * Show the application logout.
   *
   * @return \Illuminate\Http\Response
   */
  public function getLogout()
  {
    auth()->guard('admin')->logout();
    \Session::flush();

    return redirect('admin/guard/login')->send();
  }
}
