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
Route::group(['middleware' => 'api', 'namespace' => 'API'], function () {
    Route::match(['post', 'options'], 'login', ['as' => 'login', 'uses' => 'AuthController@login']);
    Route::post('register', 'AuthController@register');
});
Route::group(['middleware' => ['api', 'auth:api'], 'namespace' => 'API'], function () {

    //商户用户相关
    Route::post('details', ['as' => 'detail', 'uses' => 'AuthController@details']);
    Route::get('user', ['as' => 'user', 'uses' => 'AuthController@user']);
    Route::match(['get', 'options'], 'logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

    //菜单相关
    Route::group(['prefix' => 'menu'], function () {
        $namePrefix='menu.';
        $controller = 'MenuController@';
        Route::match(['get', 'options'], 'get-all-menu', ['as' => $namePrefix.'allPartnerMenu', 'uses' => $controller.'getAllMenu']);
    });

    //用户组相关
    Route::group(['prefix' => 'partner-admin-group'], function () {
        $namePrefix='partnerAdminGroup.';
        $controller = 'PartnerAdminGroupController@';
        //添加管理员角色
        Route::match(['post', 'options'], 'create', ['as' => $namePrefix.'create', 'uses' => $controller.'create']);
        //获取管理员角色
        Route::match(['get', 'options'], 'detail', ['as' => $namePrefix.'detail', 'uses' => $controller.'index']);
        //编辑管理员角色
        Route::match(['post', 'options'], 'edit', ['as' => $namePrefix.'edit', 'uses' => $controller.'edit']);
    });

});
