<?php
/**
 * @Author: Fish
 * @Date:   2019/7/8 17:53
 */

//后台系统公用
Route::group(['prefix' => 'sys', 'namespace' => 'System'], function () {
    $namePrefix = 'backend-api.SystemController.';
    $controller = 'SystemController@';
    //图片上传
    Route::match(['post', 'options'], 'upload', ['as' => $namePrefix . 'upload', 'uses' => $controller . 'uploadPic']);
});