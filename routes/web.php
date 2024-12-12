<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GuardController;
use App\Http\Controllers\Admin\Standard\HomeController;
use App\Http\Controllers\Admin\Standard\ModeratorController;
use App\Http\Controllers\Admin\Standard\UserController;
use App\Http\Controllers\Admin\Email\InquiryController;
use App\Http\Controllers\Admin\Email\TemplateController;
use App\Http\Controllers\Admin\Requirement\SetupController;

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

Route::get('/', function () {
  return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::match(['get', 'post'], 'admin/guard/login',[GuardController::class, 'actionLogin']);
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
