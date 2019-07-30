<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-20 14:42:53
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-20 16:32:29
 */
namespace App\Lib\Common;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheRelated
{
    /**
     * @param  string $key
     * @return void
     */
    public function delete($key): void
    {
        if (Cache::has($key)) {
            Cache::forget($key);
        }
    }

    /**
     * @param  $picStr
     * @param  $delimiter
     * @return void
     */
    public static function deleteCachePic($picStr, $delimiter = null): void
    {
        $cacheKey = 'cache_pic';
        if ($delimiter === null) {
            $picArr = (array) $picStr;
        } else {
            $picArr = explode($delimiter, $picStr);
        }
        if (Cache::has($cacheKey)) {
            $cachePic = Cache::get($cacheKey);
            foreach ($picArr as $picName) {
                if (array_key_exists($picName, $cachePic)) {
                    unset($cachePic[$picName]);
                }
            }
            $hourToStore = 24 * 2;
            $expiresAt = Carbon::now()->addHours($hourToStore);
            Cache::put($cacheKey, $cachePic, $expiresAt);
        }
    }
}
