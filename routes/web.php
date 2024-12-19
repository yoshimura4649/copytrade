<?php

use App\Http\Controllers\Admin\Email\InquiryController;
use App\Http\Controllers\Admin\Email\TemplateController;
use App\Http\Controllers\Admin\GuardController;
use App\Http\Controllers\Admin\Requirement\SetupController;
use App\Http\Controllers\Admin\Standard\HomeController;
use App\Http\Controllers\Admin\Standard\ModeratorController;
use App\Http\Controllers\Admin\Standard\UserController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Register
Route::group(['prefix' => 'register'], function () {
  Route::match(['get', 'post'], '/', [RegisterController::class, 'actionRegister'])->name('register');
  Route::post('/confirm', [RegisterController::class, 'postRegisterConfirm'])->name('register_confirm');
  Route::get('/complete', [RegisterController::class, 'getRegisterComplete'])->name('register_complete');
});

// Login - Logout
Route::match(['get', 'post'], 'login', [AuthController::class, 'actionLogin'])->name('login');
Route::get('logout', [AuthController::class, 'getLogout'])->name('logout');

// Reset Password
Route::match(['get', 'post'], 'forgot_password', [AuthController::class, 'actionForgotPassword'])->name('forgot_password');
Route::get('forgot_password/complete', [AuthController::class, 'getForgotPasswordComplete'])->name('forgot_password.complete');
Route::match(['get', 'post'], 'reset_password', [AuthController::class, 'actionResetPassword'])->name('reset_password');
Route::get('reset_password/complete', [AuthController::class, 'getResetPasswordComplete'])->name('reset_password.complete');

// Mypage Fronted
Route::group(['middleware' => ['auth:web'], 'prefix' => '/mypage'], function () {
  Route::get('', function () {
    return view('frontend.index');
  })->name('mypage');

  Route::match(['get', 'post'], 'account', [AccountController::class, 'actionAccount'])->name('account');

  // Transaction
  Route::get('transaction/history/list', [TransactionController::class, 'getList'])->name('transaction_history_list');
  Route::get('transaction/history/detail/{id}', [TransactionController::class, 'getDetail'])->name('transaction_history_detail');
  Route::post('transaction/history/detail/{id}', [TransactionController::class, 'postDetail'])->name('transaction_history_detail');
  Route::match(['get', 'post'], 'transaction/setting', [TransactionController::class, 'actionSetting'])->name('transaction_setting');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::match(['get', 'post'], 'admin/guard/login', [GuardController::class, 'actionLogin']);
Route::get('admin/guard/logout', [GuardController::class, 'getLogout']);

Route::group(['prefix' => 'admin'], function () {
  // Admin Dashboard
  Route::get('standard/home', [HomeController::class, 'getIndex']);

  Route::get('standard/moderator/list', [ModeratorController::class, 'getList']);
  Route::get('standard/moderator/detail/{id}', [ModeratorController::class, 'getDetail']);
  Route::post('standard/moderator/detail/{id}', [ModeratorController::class, 'postDetail']);
  Route::delete('standard/moderator/detail/{id}', [ModeratorController::class, 'deleteDetail']);
  Route::get('standard/moderator/new', [ModeratorController::class, 'getNew']);
  Route::post('standard/moderator/new', [ModeratorController::class, 'postNew']);
  Route::get('standard/moderator/copy/{id}', [ModeratorController::class, 'getCopy']);
  Route::post('standard/moderator/copy/{id}', [ModeratorController::class, 'postCopy']);

  Route::get('standard/user/list', [UserController::class, 'getList']);
  Route::get('standard/user/detail/{id}', [UserController::class, 'getDetail']);
  Route::post('standard/user/detail/{id}', [UserController::class, 'postDetail']);
  Route::delete('standard/user/detail/{id}', [UserController::class, 'deleteDetail']);
  Route::get('standard/user/new', [UserController::class, 'getNew']);
  Route::post('standard/user/new', [UserController::class, 'postNew']);
  Route::get('standard/user/copy/{id}', [UserController::class, 'getCopy']);
  Route::post('standard/user/copy/{id}', [UserController::class, 'postCopy']);

  Route::get('email/inquiry/list', [InquiryController::class, 'getList']);
  Route::get('email/inquiry/detail/{id}', [InquiryController::class, 'getDetail']);
  Route::post('email/inquiry/detail/{id}', [InquiryController::class, 'postDetail']);

  Route::get('email/template/list', [TemplateController::class, 'getList']);
  Route::get('email/template/detail/{id}', [TemplateController::class, 'getDetail']);
  Route::post('email/template/detail/{id}', [TemplateController::class, 'postDetail']);
  Route::delete('email/template/detail/{id}', [TemplateController::class, 'deleteDetail']);
  Route::get('email/template/new', [TemplateController::class, 'getNew']);
  Route::post('email/template/new', [TemplateController::class, 'postNew']);

  Route::get('requirement/setup/detail', [SetupController::class, 'getDetail']);
  Route::post('requirement/setup/detail', [SetupController::class, 'postDetail']);
});
