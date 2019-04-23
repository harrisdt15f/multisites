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
Route::get('/test', ['as' => 'test', 'uses' => 'TestController@test']);
Route::group(['middleware' => ['auth']], function () {
	Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
	Route::group(['prefix' => 'menu'], function () {
		Route::get('/', ['as' => 'menu.setting', 'uses' => 'MenuSettingController@index']);
		Route::post('/add', ['as' => 'menu.add', 'uses' => 'MenuSettingController@add']);
		Route::post('/edit', ['as' => 'menu.edit', 'uses' => 'MenuSettingController@edit']);
		Route::post('/delete', ['as' => 'menu.delete', 'uses' => 'MenuSettingController@delete']);
		Route::post('/changeParent', ['as' => 'menu.changeParent', 'uses' => 'MenuSettingController@changeParent']);
	});
});
