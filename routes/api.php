<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('users', 'UsersController@index');
Route::get('me', 'UsersController@me');
Route::post('user', 'UsersController@store');
Route::post('user/update', 'UsersController@update');
Route::get('permissions', 'UsersController@listPermissions');
Route::post('permissions', 'UsersController@updatePermissions');

Route::get('articles', 'ArticlesController@index');
Route::get('article', 'ArticlesController@show');
Route::post('article', 'ArticlesController@store');
Route::post('article/update', 'ArticlesController@update');


