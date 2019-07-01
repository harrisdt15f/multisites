<?php
//玩家管理-帐变类型
Route::group(['prefix' => 'accountChangeType', 'namespace' => 'Users\Fund'], function () {
    $namePrefix = 'backend-api.accountChangeType.';
    $controller = 'AccountChangeTypeController@';
    //帐变类型列表
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    //添加帐变类型
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑帐变类型
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除帐变类型
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    //操作帐变类型时需要的字段列表
    Route::match(['get', 'options'], 'param-list', ['as' => $namePrefix . 'param-list', 'uses' => $controller . 'paramList']);
});
