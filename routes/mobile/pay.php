<?php


Route::group(['prefix' => 'pay', 'namespace' => 'Pay'], function () {
    $namePrefix = 'mobile-api.PayController.';
    $controller = 'PayController@';

    //查询充值渠道
    Route::match(['get', 'options'], 'get-recharge-channel', ['as' => $namePrefix . 'getRechargeChannel',
        'uses' => $controller . 'getRechargeChannel']);

    //发起充值
    Route::match(['post', 'options'], 'recharge', ['as' => $namePrefix . 'recharge',
        'uses' => $controller . 'recharge']);
    //充值回调
    Route::match(['post', 'options'], 'recharge_callback', ['as' => $namePrefix . 'recharge_callback',
        'uses' => 'PayRechargeCallbackController@rechargeCallback']);

    //发起提现
    Route::match(['post', 'options'], 'withdraw', ['as' => $namePrefix . 'withdraw',
        'uses' => $controller . 'withdraw']);
});
