<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/14/2019
 * Time: 5:25 PM
 */

if (!function_exists('configure')) {
    function configure($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('Configure');
        } else {
            return app('Configure')->get($key, $default);
        }
    }
}