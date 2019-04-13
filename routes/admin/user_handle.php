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
    //用户信息表
    Route::match(['get', 'options'], 'users-info', ['as' => $namePrefix . 'users-info', 'uses' => $controller . 'usersInfo']);
    //给用户申请密码更换
    Route::match(['post', 'options'], 'reset-password', ['as' => $namePrefix . 'reset-password', 'uses' => $controller . 'applyResetUserPassword']);
    //用户密码已申请列表
    Route::match(['get', 'options'], 'reset-password-list', ['as' => $namePrefix . 'reset-password-list', 'uses' => $controller . 'appliedResetUserPasswordLists']);
    //给用户申请资金密码更换
    Route::match(['post', 'options'], 'reset-fund-password', ['as' => $namePrefix . 'reset-fund-password', 'uses' => $controller . 'resetUserFundPassword']);
});