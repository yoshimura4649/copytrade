<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GuardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# CORE-API-01
Route::post('/auth/login', [GuardController::class, 'postLogin']);
# CORE-API-04
Route::post('/auth/forgot_password', [GuardController::class, 'postForgotPassword']);
# CORE-API-05
Route::post('/auth/reset_password', [GuardController::class, 'postResetPassword']);
# CORE-API-06
Route::post('/auth/register', [GuardController::class, 'postRegister']);

Route::group(['middleware' => ['auth']], function () {
    # CORE-API-02
    Route::post('/auth/change_password', [GuardController::class, 'postChangePassword']);
    # CORE-API-03
    Route::get('/auth/logout', [GuardController::class, 'getLogout']);
});
