<?php

Route::group(['prefix' => 'user-agent-center'], function () {
    $namePrefix = 'web-api.UserAgentCenterController.';
    $controller = 'UserAgentCenterController@';

    Route::match(['get', 'options'], 'user-profits', ['as' => $namePrefix . 'user-profits', 'uses' => $controller . 'UserProfits']);
    Route::match(['get', 'options'], 'user-daysalary', ['as' => $namePrefix . 'user-daysalary', 'uses' => $controller . 'UserDaysalary']);

});
