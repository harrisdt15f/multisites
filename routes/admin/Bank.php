<?php
//银行管理
Route::group(['prefix' => 'bank'], function () {
    $namePrefix = 'bank.';
    $controller = 'BankController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add-bank', ['as' => $namePrefix . 'add-bank', 'uses' => $controller . 'addBank']);
    Route::match(['post', 'options'], 'edit-bank', ['as' => $namePrefix . 'edit-bank', 'uses' => $controller . 'editBank']);
    Route::match(['post', 'options'], 'delete-bank', ['as' => $namePrefix . 'delete-bank', 'uses' => $controller . 'deleteBank']);
});
