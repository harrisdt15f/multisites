<?php
//主页管理
Route::group(['prefix' => 'homepage', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'homepage.';
    $controller = 'HomepageController@';
    Route::match(['get', 'options'], 'nav-one', ['as' => $namePrefix . 'nav-one', 'uses' => $controller . 'navOne']);
    Route::match(['get', 'options'], 'page-model', ['as' => $namePrefix . 'page-model', 'uses' => $controller . 'pageModel']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'upload-pic', ['as' => $namePrefix . 'upload-pic', 'uses' => $controller . 'uploadPic']);
});

//热门彩票管理
Route::group(['prefix' => 'popular-lotteries', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'popularLotteries.';
    $controller = 'PopularLotteriesController@';
    Route::match(['get', 'options'], 'detail-one', ['as' => $namePrefix . 'detail-one', 'uses' => $controller . 'detailOne']);
    Route::match(['get', 'options'], 'detail-two', ['as' => $namePrefix . 'detail-two', 'uses' => $controller . 'detailTwo']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    Route::match(['get', 'options'], 'lotteries-list', ['as' => $namePrefix . 'lotteries-list', 'uses' => $controller . 'lotteriesList']);
    Route::match(['post', 'options'], 'lotteries-sort', ['as' => $namePrefix . 'lotteries-sort', 'uses' => $controller . 'lotteriesSort']);
});
