<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:24:38
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-30 14:38:35
 */

//开发管理-玩法等级
Route::group(['prefix' => 'method-level', 'namespace' => 'DeveloperUsage\MethodLevel'], function () {
    $namePrefix = 'backend-api.methodLevel.';
    $controller = 'MethodLevelController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});
