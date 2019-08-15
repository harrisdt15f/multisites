<?php namespace App\Lib;

use App\Models\Admin\SystemConfiguration;
use Illuminate\Support\Facades\Cache;

class Configure
{
    //system_configurations
    public function get($key, $default = null)
    {
        return Cache::tags('configure')->get($key, static function () use ($key, $default) {
            $res = SystemConfiguration::where('sign', '=', $key)->where('status', '=', 1)->first();
            if (!is_null($res)) {
                Cache::tags('configure')->forever($key, $res->value);
                return $res->value;
            } else {
                return $default;
            }
        });
    }

    public function set($key, $value)
    {
        SystemConfiguration::where('sign', '=', $key)->update(['value' => $value]);
        Cache::tags('configure')->forget($key);
    }

    public function flush()
    {
        Cache::tags('configure')->flush();
    }
}
