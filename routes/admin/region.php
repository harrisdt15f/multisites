<?php

//配置相关
Route::group(['prefix' => 'region'], function () {
	$namePrefix = 'region.';
	$controller = 'RegionController@';
	// Route::get('get_sub_configures', ['as' => $namePrefix, 'uses' => $controller.'get_sub_configures']);
	// echo $controller . 'get_sub_configures';exit;
	Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
	Route::match(['post', 'options'], 'get_town', ['as' => $namePrefix . 'get_town', 'uses' => $controller . 'get_town']);
});