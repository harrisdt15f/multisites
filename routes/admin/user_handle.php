<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/11/2019
 * Time: 12:44 PM
 */

//管理总代用户与玩家
Route::group(['prefix' => 'user-handle'], function () {
    $namePrefix = 'userhandle.';
    $controller = 'UserHandleController@';
    //创建总代
    Route::match(['post', 'options'], 'create-user', ['as' => $namePrefix . 'create-user', 'uses' => $controller . 'createUser']);
    //创建总代时获取当前平台的奖金组
    Route::match(['get', 'options'], 'prizegroup', ['as' => $namePrefix . 'prizegroup', 'uses' => $controller . 'getUserPrizeGroup']);
});