<?php

namespace App\Models\Admin\Logics;

trait SysConfiguresTraits
{

    /**
     * @param  string  $key
     * @return string
     */
    public static function getConfigValue($key = null):  ? string
    {
        if (empty($key)) {
            return $key;
        } else {
            return self::where('sign', $key)->value('value');
        }
    }

}
