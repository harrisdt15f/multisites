<?php
//报表管理
Route::group(['prefix' => 'reportManagement', 'namespace' => 'Report'], function () {
    $namePrefix = 'backend-api.reportManagement.';
    $controller = 'reportManagementController@';
    //玩家帐变报表
    Route::match(['post', 'options'], 'user-account-change', ['as' => $namePrefix . 'user-account-change', 'uses' => $controller . 'userAccountChange']);
    //玩家充值报表
    Route::match(['post', 'options'], 'user-recharge-history', ['as' => $namePrefix . 'user-recharge-history', 'uses' => $controller . 'userRechargeHistory']);
});
