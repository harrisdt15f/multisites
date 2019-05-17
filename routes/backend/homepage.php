<?php
//主页管理
Route::group(['prefix' => 'homepage', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'homepage.';
    $controller = 'HomepageController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
});
