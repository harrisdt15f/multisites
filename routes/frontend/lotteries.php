<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/17/2019
 * Time: 2:17 PM
 */
//管理总代用户与玩家
Route::group(['prefix' => 'lotteries','namespace'=>'Game\Lottery'], function () {
    $namePrefix = 'web-api.LottriesController.';
    $controller = 'LottriesController@';
    //创建总代
    Route::match(['post', 'options'], 'lotteryList', ['as' => $namePrefix . 'lotteryList', 'uses' => $controller . 'lotteryList']);
});