<?php


//配置相关
Route::group(['prefix' => 'partner-sys-configures'], function () {
    $namePrefix = 'configures.';
    $controller = 'ConfiguresController@';
	// Route::get('get_sub_configures', ['as' => $namePrefix, 'uses' => $controller.'get_sub_configures']);
	// echo $controller . 'get_sub_configures';exit;
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'getConfiguresList']);
});