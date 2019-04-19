<?php

//配置相关
Route::group(['prefix' => 'partner-sys-configures'], function () {
	$namePrefix = 'configures.';
	$controller = 'ConfiguresController@';
	// Route::get('get_sub_configures', ['as' => $namePrefix, 'uses' => $controller.'get_sub_configures']);
	// echo $controller . 'get_sub_configures';exit;
	Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'getConfiguresList']);
	Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
	Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
	Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});