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

    /**
     * 获取网站基本配置
     * @param  integer $update [不等于0时  更新缓存]
     * @return array
     */
    public static function getWebInfo($update = 0) : array
    {
        $redisKey = 'web_info';
        $data = [];
        if (Cache::has($redisKey) && $update === 0) {
            $data = Cache::get($redisKey);
        } else {
            $sysConfigEloq = self::where('sign', 'web_info')->first();
            if ($sysConfigEloq !== null) {
                $webConfigELoq = $sysConfigEloq->childs;
                foreach ($webConfigELoq as $webConfigItem) {
                    $data[$webConfigItem->sign] = $webConfigItem->value;
                }
            }
            Cache::forever($redisKey, $data);
        }
        return $data;
    }
}
