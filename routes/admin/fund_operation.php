<?php
//额度管理
Route::group(['prefix' => 'fundOperation'], function () {
    $namePrefix = 'fundOperation.';
    $controller = 'FundOperationController@';
    Route::match(['post', 'options'], 'users', ['as' => $namePrefix . 'users', 'uses' => $controller . 'users']);
    Route::match(['post', 'options'], 'add-fund', ['as' => $namePrefix . 'add-fund', 'uses' => $controller . 'addFund']);
    Route::match(['post', 'options'], 'every-day-fund', ['as' => $namePrefix . 'every-day-fund', 'uses' => $controller . 'everyDayFund']);
});
