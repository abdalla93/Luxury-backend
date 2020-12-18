<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;



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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user/posts', [UserController::class, 'getUserPosts']);
    Route::get('/posts/{tagName}', [PostController::class, 'getPostsByUser']);
    Route::resource('/user', UserController::class);
    Route::resource('/post', PostController::class);

});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',  [AuthController::class, 'register']);

