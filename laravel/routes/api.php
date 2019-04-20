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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user', 'UserController@index');
Route::get('user/{id}', 'UserController@show');
Route::post('user', 'UserController@store');
Route::put('user/{id}', 'UserController@update');
Route::delete('user/{id}', 'UserController@delete');
Route::post('user/avatar', 'UserController@uploadAvatar');

Route::get('lesson', 'LessonController@index');
Route::get('lesson/{id}', 'LessonController@show');
Route::post('lesson', 'LessonController@store');
Route::put('lesson/{id}', 'LessonController@update');
Route::delete('lesson/{id}', 'LessonController@delete');
Route::post('lesson/avatar', 'LessonController@uploadAvatar');