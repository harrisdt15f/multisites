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
    //热门彩票
    Route::match(['get', 'options'], 'popular-lotteries', ['as' => $namePrefix . 'popular-lotteries', 'uses' => $controller . 'popularLotteries']);
    //热门玩法
    Route::match(['get', 'options'], 'popular-methods', ['as' => $namePrefix . 'popular-methods', 'uses' => $controller . 'popularMethods']);
    //二维码
    Route::match(['get', 'options'], 'qrcode', ['as' => $namePrefix . 'qrcode', 'uses' => $controller . 'qrCode']);
    //热门活动
    Route::match(['get', 'options'], 'activity', ['as' => $namePrefix . 'activity', 'uses' => $controller . 'activity']);
    //LOGO
    Route::match(['get', 'options'], 'logo', ['as' => $namePrefix . 'logo', 'uses' => $controller . 'logo']);
    //公告
    Route::match(['post', 'options'], 'notice', ['as' => $namePrefix . 'notice', 'uses' => $controller . 'notice']);
    //ico
    Route::match(['get', 'options'], 'ico', ['as' => $namePrefix . 'ico', 'uses' => $controller . 'ico']);
});
