<?php
//开发管理-前端模块管理
Route::group(['prefix' => 'frontend-allocated-model'], function () {
    $namePrefix = 'frontendAllocatedModel.';
    $controller = 'FrontendAllocatedModelController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});
