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
    Route::match(['get', 'options'], 'get-all-menu', ['as' => $namePrefix . 'allPartnerMenu', 'uses' => $controller . 'getAllMenu']);
});