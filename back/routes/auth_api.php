<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login','AuthController@Login');
Route::post('/register','AuthController@Register');
Route::post('/resend-otp','AuthController@ResendActivateCode');
Route::post('/active','AuthController@ActiveAccount');
Route::post('/forget-password','AuthController@ForgetPassword');
Route::post('/forget-password-otp','AuthController@PasswordRecovery');
Route::post('/forget-password-otp-resend','AuthController@ResendPasswordRecoveryCode');

