<?php

//内容管理
Route::group(['prefix' => 'content'], function () {
    $namePrefix = 'content.';
    $controller = 'ArticlesController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add-articles', ['as' => $namePrefix . 'add-articles', 'uses' => $controller . 'addArticles']);
    Route::match(['post', 'options'], 'edit-articles', ['as' => $namePrefix . 'edit-articles', 'uses' => $controller . 'editArticles']);
    Route::match(['post', 'options'], 'delete-articles', ['as' => $namePrefix . 'delete-articles', 'uses' => $controller . 'deleteArticles']);
    Route::match(['post', 'options'], 'sort-articles', ['as' => $namePrefix . 'sort-articles', 'uses' => $controller . 'sortArticles']);
    Route::match(['post', 'options'], 'top-articles', ['as' => $namePrefix . 'top-articles', 'uses' => $controller . 'topArticles']);
    Route::match(['post', 'options'], 'upload-pic', ['as' => $namePrefix . 'upload-pic', 'uses' => $controller . 'uploadPic']);
    //分类管理
    Route::match(['get', 'options'], 'category', ['as' => $namePrefix . 'category', 'uses' => 'CategoryController@detail']);
    Route::match(['get', 'options'], 'category-select', ['as' => $namePrefix . 'category-select', 'uses' => 'CategoryController@select']);
});
