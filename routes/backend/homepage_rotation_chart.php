<?php
//主页管理-轮播图
Route::group(['prefix' => 'homepage-rotation-chart', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'homepageRotationChart.';
    $controller = 'HomepageRotationChartController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});
