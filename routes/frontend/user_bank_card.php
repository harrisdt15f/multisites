<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 16:39:51
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 16:56:35
 */
Route::group(['prefix' => 'user-bank-card', 'namespace' => 'User\Fund'], function () {
    $namePrefix = 'web-api.UserBankCardController.';
    $controller = 'UserBankCardController@';
    //用户银行卡列表
    Route::match(['get', 'options'], 'lists', ['as' => $namePrefix . 'lists', 'uses' => $controller . 'lists']);
    //用户添加绑定银行卡
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //用户删除绑定银行卡
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});
