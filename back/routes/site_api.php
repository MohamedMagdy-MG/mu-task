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

Route::prefix('profile')->group(function () {
    Route::get('/', 'ProfileController@Profile');
    Route::post('/', 'ProfileController@UpdateProfile');
    Route::post('/password', 'ProfileController@UpdatePassword');
});

Route::prefix('post')->group(function () {
    Route::get('/', 'PostController@Index');
    Route::post('/', 'PostController@Add');
    Route::post('/update', 'PostController@Update');
    Route::delete('/', 'PostController@Delete');
    Route::post('/react', 'PostController@React');
});
Route::prefix('comment')->group(function () {
    Route::get('/', 'CommentController@Index');
    Route::post('/', 'CommentController@Add');
    Route::put('/', 'CommentController@Update');
    Route::delete('/', 'CommentController@Delete');
    Route::delete('/selected', 'CommentController@DeleteSelected');
});

Route::post('/logout', 'ProfileController@Logout');
