<?php
//玩家管理-人工充值
Route::group(['prefix' => 'artificialRecharge'], function () {
    $namePrefix = 'artificialRecharge.';
    $controller = 'artificialRechargeController@';
    Route::match(['post', 'options'], 'users', ['as' => $namePrefix . 'users', 'uses' => $controller . 'users']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'recharge']);
});
