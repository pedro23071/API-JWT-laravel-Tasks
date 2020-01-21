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

Route::post('/user-register', 'UserController@createUser');
Route::post('/log-in', 'UserController@logIn');

Route::get('/tasks', 'TaskController@getAll');
Route::get('/task/{id}', 'TaskController@getByID');
Route::get('/task/delete/{id}', 'TaskController@destroyTask');
Route::post('/edit-task/{id}', 'TaskController@updateTask');
Route::post('/create-task', 'TaskController@createTask');
