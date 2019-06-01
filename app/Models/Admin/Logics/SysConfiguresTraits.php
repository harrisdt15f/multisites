<?php

namespace App\Models\Admin\Logics;

/**
 * @Author: LingPh
 * @Date:   2019-05-29 17:36:06
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-29 17:36:45
 */
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
