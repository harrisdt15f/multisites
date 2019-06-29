<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/17/2019
 * Time: 5:30 PM
 */

//游戏
Route::group(['prefix' => 'lotteries', 'namespace' => 'Game\Lottery'], function () {
    $namePrefix = 'backend-api.lotteries.';
    $controller = 'LotteriesController@';
    //游戏series获取接口
    Route::match(['get', 'options'], 'series-lists', ['as' => $namePrefix . 'series-lists', 'uses' => $controller . 'seriesLists']);
    //彩种列表获取接口
    Route::match(['post', 'options'], 'lotteries-lists', ['as' => $namePrefix . 'lotteries-lists', 'uses' => $controller . 'lists']);
    //彩种玩法展示接口
    Route::match(['get', 'options'], 'lotteries-method-lists', ['as' => $namePrefix . 'lotteries-method-lists', 'uses' => $controller . 'methodLists']);
    //奖期展示
    Route::match(['post', 'options'], 'lotteries-issue-lists', ['as' => $namePrefix . 'lotteries-issue-lists', 'uses' => $controller . 'issueLists']);
    //奖期生成接口
    Route::match(['post', 'options'], 'lotteries-issue-generate', ['as' => $namePrefix . 'lotteries-issue-generate', 'uses' => $controller . 'generateIssue']);
    //彩种开关
    Route::match(['post', 'options'], 'lotteries-switch', ['as' => $namePrefix . 'lotteries-switch', 'uses' => $controller . 'lotteriesSwitch']);
    //玩法组开关
    Route::match(['post', 'options'], 'method-group-switch', ['as' => $namePrefix . 'method-group-switch', 'uses' => $controller . 'methodGroupSwitch']);
    //玩法行开关
    Route::match(['post', 'options'], 'method-row-switch', ['as' => $namePrefix . 'method-row-switch', 'uses' => $controller . 'methodRowSwitch']);
    //玩法开关
    Route::match(['post', 'options'], 'method-switch', ['as' => $namePrefix . 'method-switch', 'uses' => $controller . 'methodSwitch']);
    //编辑玩法
    Route::match(['post', 'options'], 'edit-method', ['as' => $namePrefix . 'edit-method', 'uses' => $controller . 'editMethod']);
    //录号
    Route::match(['post', 'options'], 'input-code', ['as' => $namePrefix . 'input-code', 'uses' => $controller . 'inputCode']);
    //彩票号码合法长度
    Route::match(['get', 'options'], 'lotteries-code-length', ['as' => $namePrefix . 'lotteries-code-length', 'uses' => $controller . 'lotteriesCodeLength']);
    //添加彩种
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
});
