<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/17/2019
 * Time: 5:30 PM
 */

//游戏
Route::group(['prefix' => 'lotteries', 'namespace' => 'Game\Lottery'], function () {
    $namePrefix = 'lotteries.';
    $controller = 'LotteriesController@';
    //游戏series获取接口
    Route::match(['get', 'options'], 'series-lists', ['as' => $namePrefix . 'series-lists', 'uses' => $controller . 'seriesLists']);
    //彩种列表获取接口
    Route::match(['post', 'options'], 'lotteries-lists', ['as' => $namePrefix . 'lotteries-lists', 'uses' => $controller . 'lotteriesLists']);
    //彩种玩法展示接口
    Route::match(['get', 'options'], 'lotteries-method-lists', ['as' => $namePrefix . 'lotteries-method-lists', 'uses' => $controller . 'lotteriesMethodLists']);
    //奖期展示
    Route::match(['post', 'options'], 'lotteries-issue-lists', ['as' => $namePrefix . 'lotteries-issue-lists', 'uses' => $controller . 'lotteryIssueLists']);
    //奖期生成接口
    Route::match(['post', 'options'], 'lotteries-issue-generate', ['as' => $namePrefix . 'lotteries-issue-generate', 'uses' => $controller . 'generateIssue']);
    //彩种开关
    Route::match(['post', 'options'], 'lotteries-switch', ['as' => $namePrefix . 'lotteries-issue-generate', 'uses' => $controller . 'lotteriesSwitch']);
    //玩法组开关
    Route::match(['post', 'options'], 'method-group-switch', ['as' => $namePrefix . 'method-group-switch', 'uses' => $controller . 'methodGroupSwitch']);
    //玩法行开关
    Route::match(['post', 'options'], 'method-row-switch', ['as' => $namePrefix . 'method-row-switch', 'uses' => $controller . 'methodRowSwitch']);
    //玩法开关
    Route::match(['post', 'options'], 'method-switch', ['as' => $namePrefix . 'method-switch', 'uses' => $controller . 'methodSwitch']);
});
