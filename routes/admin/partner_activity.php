<?php

//活动相关
Route::group(['prefix' => 'activity'], function () {
	$namePrefix = 'activity.';
	$controller = 'ActivityInfosController@';
	Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
	Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
	Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
	Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
	//活动类型列表
	Route::match(['get', 'options'], 'type', ['as' => $namePrefix . 'type', 'uses' => 'ActivityTypeController@detail']);
});