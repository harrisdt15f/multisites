<?php

Route::group(['prefix' => 'user-fund', 'namespace' => 'User\Fund'], function () {
    $namePrefix = 'mobile-api.UserFundController.';
    $controller = 'UserFundController@';
    //用户账变记录
    Route::match(['post', 'options'], 'lists', ['as' => $namePrefix . 'lists', 'uses' => $controller . 'lists']);
});
