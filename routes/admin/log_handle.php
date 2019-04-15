<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/15/2019
 * Time: 9:01 PM
 */

//管理总代用户与玩家
Route::group(['prefix' => 'log'], function () {
    $namePrefix = 'loghandle.';
    $controller = 'HandleLogController@';
    //搜索日志列表
    Route::match(['post', 'options'], 'list', ['as' => $namePrefix . 'list', 'uses' => $controller . 'details']);
});