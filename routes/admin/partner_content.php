<?php

//内容管理
Route::group(['prefix' => 'content'], function () {
    $namePrefix = 'content.';
    // $controller = 'ConfiguresController@';
    // Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'getConfiguresList']);
    // Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    // Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    // Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    // Route::match(['post', 'options'], 'switch', ['as' => $namePrefix . 'switch', 'uses' => $controller . 'switch']);
    //分类管理
    Route::match(['get', 'options'], 'category', ['as' => $namePrefix . 'category', 'uses' => 'CategoryController@detail']);
});
