<?php
//玩家管理-充值审核
Route::group(['prefix' => 'recharge-check'], function () {
    $namePrefix = 'RechargeCheck.';
    $controller = 'RechargeCheckController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'audit-success', ['as' => $namePrefix . 'audit-success', 'uses' => $controller . 'auditSuccess']);
    Route::match(['post', 'options'], 'audit-failure', ['as' => $namePrefix . 'audit-failure', 'uses' => $controller . 'auditFailure']);
});
