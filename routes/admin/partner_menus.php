<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/11/2019
 * Time: 12:24 PM
 */

//菜单相关
Route::group(['prefix' => 'menu'], function () {
    $namePrefix = 'menu.';
    $controller = 'MenuController@';
    //获取商户用户的所有菜单
    Route::match(['get', 'options'], 'get-all-menu', ['as' => $namePrefix . 'allPartnerMenu', 'uses' => $controller . 'getAllMenu']);
    //获取当前商户用户的菜单
    Route::match(['get', 'options'], 'current-admin-menu', ['as' => $namePrefix . 'current-admin-menu', 'uses' => $controller . 'currentPartnerMenu']);
});