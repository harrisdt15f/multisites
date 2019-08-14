<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/14/2019
 * Time: 6:14 PM
 */

namespace App\Providers;

use App\Lib\Configure;
use Illuminate\Support\ServiceProvider;

class ConfigureServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton('Configure', static function () {
            return new Configure();
        });
    }
}
