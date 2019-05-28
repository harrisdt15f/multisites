<?php
//公告管理
Route::group(['prefix' => 'notice', 'namespace' => 'Admin\Notice'], function () {
    $namePrefix = 'backend-api.notice.';
    $controller = 'NoticeController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    Route::match(['post', 'options'], 'sort', ['as' => $namePrefix . 'sort', 'uses' => $controller . 'sort']);
    Route::match(['post', 'options'], 'top', ['as' => $namePrefix . 'top', 'uses' => $controller . 'top']);
});
