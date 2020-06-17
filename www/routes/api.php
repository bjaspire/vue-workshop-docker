<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post("login", "AuthController@login");
// Route::post("register", "AuthController@register");

Route::group([
    'prefix' => 'v1',
    'namespace' => 'API',
], function ($router) {
    Route::post('register', 'User\AuthController@register');
    Route::post('login', 'User\AuthController@login');
    Route::post('bj', 'User\AuthController@bj');
});

Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'v1',
    'namespace' => 'API',
], function ($router) {
    Route::post('logout', 'User\AuthController@logout');
    Route::post('refresh', 'User\AuthController@refresh');
    Route::get('me', 'User\AuthController@me');
    Route::resource('transaction', 'Transaction\TransactionsController', ['except' => ['create', 'edit']]);
});

// Route::group(["middleware" => "auth.jwt"], function () {
//     Route::get("logout", "AuthController@logout");
//     Route::resource("tasks", "TaskController");
// });