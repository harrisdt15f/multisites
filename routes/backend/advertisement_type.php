<?php
//广告管理-广告分类
Route::group(['prefix' => 'advertisement-type', 'namespace' => 'Admin\Advertisement'], function () {
    $namePrefix = 'advertisementType.';
    $controller = 'AdvertisementTypeController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
});
