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
     * @param  array  $picNames
     * @return void
     */
    public function deleteCachePic(array $picNames): void
    {
        if (Cache::has('cache_pic')) {
            $cachePic = Cache::get('cache_pic');
            foreach ($picNames as $picName) {
                if (array_key_exists($picName, $cachePic)) {
                    unset($cachePic[$picName]);
                }
            }
            $hourToStore = 24 * 2;
            $expiresAt = Carbon::now()->addHours($hourToStore);
            Cache::put('cache_pic', $cachePic, $expiresAt);
        }
    }
}
