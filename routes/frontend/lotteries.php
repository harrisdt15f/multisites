<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/17/2019
 * Time: 2:17 PM
 */
//游戏接口
Route::group(['prefix' => 'lotteries','namespace'=>'Game\Lottery'], function () {
    $namePrefix = 'web-api.LottriesController.';
    $controller = 'LottriesController@';
    //获取彩种接口
    Route::match(['post', 'options'], 'lotteryList', ['as' => $namePrefix . 'lotteryList', 'uses' => $controller . 'lotteryList']);
    //获取彩种接口
    Route::match(['post', 'options'], 'lotteryInfo', ['as' => $namePrefix . 'lotteryInfo', 'uses' => $controller . 'lotteryInfo']);
});