<?php

//活动相关
Route::group(['prefix' => 'activity'], function () {
	$namePrefix = 'activity.';
	$controller = 'ActivityInfosController@';
	// Route::get('get_sub_configures', ['as' => $namePrefix, 'uses' => $controller.'get_sub_configures']);
	// echo $controller . 'get_sub_configures';exit;
	Route::match(['post', 'options'], 'add_info', ['as' => $namePrefix . 'add_info', 'uses' => $controller . 'add_info']);
	// Route::match(['post', 'options'], 'get_town', ['as' => $namePrefix . 'get_town', 'uses' => $controller . 'get_town']);
});