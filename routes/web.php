<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 提现记录相关
/*Route::group(['prefix' => '/', 'middleware' => 'client.auth', 'namespace' => "Client\Withdraw"], function () {
    // 提现
    $controller = 'HomeController@';
    Route::any('list', ['as' => 'withdrawList', 'uses' => $controller . 'index']);
});*/



Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
});

