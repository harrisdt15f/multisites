<?php

Route::group(['prefix' => 'user-agent-center'], function () {
    $namePrefix = 'web-api.UserAgentCenterController.';
    $controller = 'UserAgentCenterController@';

    Route::match(['get', 'options'], 'user-profits', ['as' => $namePrefix . 'user-profits', 'uses' => $controller . 'UserProfits']);
    Route::match(['get', 'options'], 'user-daysalary', ['as' => $namePrefix . 'user-daysalary', 'uses' => $controller . 'UserDaysalary']);
    //代理开户-奖金组最大最小值
    Route::match(['get', 'options'], 'prize-group', ['as' => $namePrefix . 'prize-group', 'uses' => $controller . 'PrizeGroup']);
    //链接开户-链接列表
    Route::match(['get', 'options'], 'registerable-link', ['as' => $namePrefix . 'registerable-link', 'uses' => $controller . 'RegisterableLink']);
    //链接开户-生成链接
    Route::match(['post', 'options'], 'register-link', ['as' => $namePrefix . 'register-link', 'uses' => $controller . 'RegisterLink']);
    Route::match(['get', 'options'], 'user-bonus', ['as' => $namePrefix . 'user-bonus', 'uses' => $controller . 'UserBonus']);

});
