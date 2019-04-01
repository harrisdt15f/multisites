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
Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', ['as' => 'detail', 'uses' => 'API\AuthController@details']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'API\AuthController@logout']);
    Route::get('user', ['as' => 'user', 'uses' => 'API\AuthController@user']);
    Route::get('menu/get-all-menu', ['as' => 'detail', 'uses' => 'API\MenuController@getAllMenu']);
});
