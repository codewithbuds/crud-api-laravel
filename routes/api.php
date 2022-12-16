<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Foundation\EmailVerificationRequest;
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

Route::post('/user-login', [AuthController::class, 'Login'])->name('login.post'); 
Route::post('/user-registration', [AuthController::class, 'Registration'])->name('register.post'); 
Route::get('/account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('user.verify'); 
Route::get('/profile', [AuthController::class,'profile'])->name('profile');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  

//routes for reset password and forget password
Route::post('/forget-password', [ForgotPasswordController::class, 'ForgetPassword'])->name('forget.password.post'); 
Route::post('/reset-password', [ForgotPasswordController::class, 'ResetPassword'])->name('reset.password.post');



