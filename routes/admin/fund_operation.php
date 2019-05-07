<?php
//额度管理
Route::group(['prefix' => 'fundOperation'], function () {
    $namePrefix = 'fundOperation.';
    $controller = 'FundOperationController@';
    Route::match(['post', 'options'], 'admins', ['as' => $namePrefix . 'admins', 'uses' => $controller . 'admins']);
    Route::match(['post', 'options'], 'add-fund', ['as' => $namePrefix . 'add-fund', 'uses' => $controller . 'addFund']);
    Route::match(['post', 'options'], 'every-day-fund', ['as' => $namePrefix . 'every-day-fund', 'uses' => $controller . 'everyDayFund']);
});
