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
//Auth::routes();
Route::group(['middleware' => 'api','namespace' => 'API'], function(){
    Route::match(['post','options'],'login','AuthController@login');
    Route::post('register','AuthController@register');
});
Route::group(['middleware' => ['api','auth:api'],'namespace' => 'API'], function(){
    //商户用户相关
    Route::post('details', ['as' => 'detail', 'uses' => 'AuthController@details']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    Route::get('user', ['as' => 'user', 'uses' => 'AuthController@user']);

    //菜单相关
    Route::get('menu/get-all-menu', ['as' => 'menu.allPartnerMenu', 'uses' => 'MenuController@getAllMenu']);

    //用户组相关
    Route::post('partner-admin-group/create', ['as' => 'partnerAdminGroup.create', 'uses' => 'PartnerAdminGroupController@create']);
});
