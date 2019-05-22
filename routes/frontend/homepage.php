<?php
/**
 * author: Alvin
 * Date: 5/21/2019
 * Time: 16:27 PM
 */

//首页
Route::group(['prefix' => 'homepage', 'namespace' => 'Homepage'], function () {
    $namePrefix = 'web-api.HomepageController.';
    $controller = 'HomepageController@';
    //首页-需要展示的模块
    Route::match(['get', 'options'], 'show-homepage-model', ['as' => $namePrefix . 'show-homepage-model', 'uses' => $controller . 'showHomepageModel']);
    //轮播图
    Route::match(['get', 'options'], 'banner', ['as' => $namePrefix . 'banner', 'uses' => $controller . 'banner']);
    //热门彩种一
    Route::match(['get', 'options'], 'popular-lotteries-one', ['as' => $namePrefix . 'popular-lotteries-one', 'uses' => $controller . 'popularLotteriesOne']);
    //热门彩种二
    Route::match(['get', 'options'], 'popular-lotteries-two', ['as' => $namePrefix . 'popular-lotteries-two', 'uses' => $controller . 'popularLotteriesTwo']);
    //二维码
    Route::match(['get', 'options'], 'qrcode', ['as' => $namePrefix . 'qrcode', 'uses' => $controller . 'qrCode']);
    //热门活动
    Route::match(['get', 'options'], 'activity', ['as' => $namePrefix . 'activity', 'uses' => $controller . 'activity']);
    //LOGO
    Route::match(['get', 'options'], 'logo', ['as' => $namePrefix . 'logo', 'uses' => $controller . 'logo']);
});
