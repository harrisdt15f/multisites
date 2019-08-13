<?php

Route::group(['prefix' => 'user-agent-center'], function () {
    $namePrefix = 'web-api.UserAgentCenterController.';
    $controller = 'UserAgentCenterController@';

    Route::match(
        ['get', 'options'],
        'user-profits',
        ['as' => $namePrefix . 'user-profits', 'uses' => $controller . 'UserProfits']
    );
    Route::match(
        ['get', 'options'],
        'user-daysalary',
        ['as' => $namePrefix . 'user-daysalary', 'uses' => $controller . 'UserDaysalary']
    );
    Route::match(
        ['get', 'options'],
        'registerable-link',
        ['as' => $namePrefix . 'registerable-link', 'uses' => $controller . 'RegisterableLink']
    );
    Route::match(
        ['post', 'options'],
        'register-link',
        ['as' => $namePrefix . 'register-link', 'uses' => $controller . 'RegisterLink']
    );
    Route::match(
        ['get', 'options'],
        'user-bonus',
        ['as' => $namePrefix . 'user-bonus', 'uses' => $controller . 'UserBonus']
    );
});
