<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/user/register', [UserAuthController::class, 'register']);

Route::post('/user/login', [UserAuthController::class, 'login']);


Route::group(['middleware' => ['auth:users']], function () {
    Route::post('/user/signout', [UserAuthController::class, 'signout']);
    Route::post('/user/updateProfile', [UserAuthController::class, 'updateProfile']);
    Route::post('/user/editPassword', [UserAuthController::class, 'editPassword']);
});


Route::post('/admin/register', [AdminController::class, 'register']);

Route::post('/admin/login', [AdminController::class, 'login']);

//Route::post('/forgot', [ForgetPasswordController::class, 'forgot']);
Route::group(['middleware' => ['auth:admins']], function () {
    Route::post('/admin/logout', [AdminController::class, 'logout']);
    Route::get('/admin/me', [AdminController::class, 'me']);
//    Route::post('/user/updateProfile', [AdminController::class, 'updateProfile']);
//    Route::post('/user/editPassword', [AdminController::class, 'editPassword']);
});

Route::post('/forgot', [ForgetPasswordController::class, 'forgot']);
Route::post('/password/reset', [ForgetPasswordController::class, 'reset'])->name('password.reset');


