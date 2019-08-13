<?php

namespace App\Models\Admin\Logics;
use Illuminate\Support\Facades\Cache;

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

    public static function getGenerateIssueTime(){
        $redisKey = 'generateIssueTime';
        if (Cache::has($redisKey)) {
            $generateIssueTime = Cache::get($redisKey);
        } else {
            $generateIssueTime = self::getConfigValue('generate_issue_time');
            Cache::forever($redisKey, $generateIssueTime);
        }
        return $generateIssueTime;
    }
}
