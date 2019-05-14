<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Auth::routes();
Route::group(['middleware' => ['api'], 'namespace' => 'BackendApi'], function () {
    Route::match(['post', 'options'], 'login', ['as' => 'login', 'uses' => 'BackendAuthController@login']);
});
Route::group(['middleware' => ['api', 'auth:backend'], 'namespace' => 'BackendApi'], function () {
    $sRouteDir = Config::get('route.admin');
    $aRouteFiles = glob($sRouteDir . '*.php');
    foreach ($aRouteFiles as $sRouteFile) {
        include($sRouteFile);
    }
    unset($aRouteFiles);
});
