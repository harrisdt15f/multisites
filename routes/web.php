<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 提现记录相关
/*Route::group(['prefix' => '/', 'middleware' => 'client.auth', 'namespace' => "Client\Withdraw"], function () {
    // 提现
    $controller = 'HomeController@';
    Route::any('list', ['as' => 'withdrawList', 'uses' => $controller . 'index']);
});*/
Route::group(['namespace' => 'WebControllers\Auth'], function () {
    Route::match(['get', 'options'], 'login', ['as' => 'login', 'uses' => 'LoginController@showLoginForm']);
    Route::match(['post', 'options'], 'login', ['as' => 'login.post', 'uses' => 'LoginController@login']);
    Route::match(['post', 'options'], 'logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);

    Route::match(['get', 'options'], 'register', ['as' => 'register', 'uses' => 'RegisterController@showRegistrationForm']);
    Route::match(['post', 'options'], 'register', ['as' => 'register.post', 'uses' => 'RegisterController@register']);
// Password Reset Routes...
    Route::match(['get', 'options'], 'password/reset', ['as' => 'password.request', 'uses' => 'ForgotPasswordController@showLinkRequestForm']);
    Route::match(['post', 'options'], 'password/email', ['as' => 'password.email', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);

    Route::match(['get', 'options'], 'password/reset/{token}', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@showResetForm']);
    Route::match(['post', 'options'], 'password/reset', ['as' => 'password.update', 'uses' => 'ResetPasswordController@reset']);
// Email Verification Routes...
    Route::get('email/verify', ['as' => 'verification.notice', 'uses' => 'VerificationController@show']);
    Route::get('email/verify/{id}', ['as' => 'verification.verify', 'uses' => 'VerificationController@verify']);
    Route::get('email/resend', ['as' => 'verification.resend', 'uses' => 'VerificationController@resend']);
    /*Route::group(['prefix' => 'menu'], function () {
    });*/
});
//Auth::routes();
Route::group(['middleware' => ['auth'], 'namespace' => 'WebControllers'], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::group(['prefix' => 'menu'], function () {
        Route::get('/', ['as' => 'menu.setting', 'uses' => 'MenuSettingController@index']);
        Route::post('/add', ['as' => 'menu.add', 'uses' => 'MenuSettingController@add']);
        Route::post('/edit', ['as' => 'menu.edit', 'uses' => 'MenuSettingController@edit']);
        Route::post('/delete', ['as' => 'menu.delete', 'uses' => 'MenuSettingController@delete']);
        Route::post('/changeParent', ['as' => 'menu.changeParent', 'uses' => 'MenuSettingController@changeParent']);
    });
});

