<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:35:16
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-01 16:02:52
 */

//运营管理-站内消息
Route::group(['prefix' => 'internal-notice-message', 'namespace' => 'Admin\Message'], function () {
    $namePrefix = 'backend-api.internalMessage.';
    $controller = 'NoticeMessagesController@';
    //获取单个管理员的站内信息
    Route::match(['post', 'options'], 'admin-messages', ['as' => $namePrefix . 'admin-messages', 'uses' => $controller . 'adminMessages']);
    //手动发送站内信息
    Route::match(['post', 'options'], 'send-messages', ['as' => $namePrefix . 'send-messages', 'uses' => $controller . 'sendMessages']);
});
