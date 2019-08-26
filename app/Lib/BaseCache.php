<?php

namespace App\Lib;

use Exception;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait BaseCache
{
    /**
     * 获取缓存
     * @param $key
     * @return Repository
     * @throws Exception
     */
    public static function getCacheData($key)
    {
        $cacheConfig = self::getCacheConfig($key);
        if (isset($cacheConfig['tags'])) {
            $result = Cache::tags($cacheConfig['tags'])->get($cacheConfig['key'], []);
            return json_decode($result, true);
        }
        return Cache::get($cacheConfig['key'], []);
    }

    /**
     * 保存
     * @param $key
     * @param $value
     * @throws Exception
     */
    public static function saveCacheData($key, $value)
    {
        $cacheConfig = self::getCacheConfig($key);
        if (isset($cacheConfig['tags'])) {
            if ($cacheConfig['expire_time'] <= 0) {
                Cache::tags($cacheConfig['tags'])->forever($cacheConfig['key'], $value);
            } else {
                $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
                Cache::tags($cacheConfig['tags'])->put($cacheConfig['key'], $value, $expireTime);
            }
        } else {
            if ($cacheConfig['expire_time'] <= 0) {
                Cache::forever($cacheConfig['key'], $value);
            } else {
                $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
                Cache::put($cacheConfig['key'], $value, $expireTime);
            }
        }
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     * @throws Exception
     */
    public static function mtsFlushCache($key): bool
    {
        $cacheConfig = self::getCacheConfig($key);
        if (isset($cacheConfig['tags'])) {
            return Cache::tags($cacheConfig['tags'])->forget($cacheConfig['key'], []);
        }
        return Cache::forget($cacheConfig['key'], []);
    }

    /**
     * 获取缓存
     * @param $key
     * @return mixed
     */
    public static function getCacheConfig($key)
    {
        $cacheConfig = config('web.main.cache');
        return $cacheConfig[$key] ?? $cacheConfig['common'];
    }

    /**
     * 检查是否存在缓存
     * @param $key
     * @return bool
     * @throws Exception
     */
    public static function hasCache($key): bool
    {
        $cacheConfig = self::getCacheConfig($key);
        if (isset($cacheConfig['tags'])) {
            return Cache::tags($cacheConfig['tags'])->has($cacheConfig['key']);
        }
        return Cache::has($cacheConfig['key']);
    }

    /**
     * @param  $picStr
     * @param  $delimiter
     * @return void
     */
    public static function deleteCachePic($picStr, $delimiter = null): void
    {
        $redisKey = 'cleaned_images';
        $cleanedImages = self::getCacheData($redisKey);
        if ($delimiter === null) {
            $picArr = (array)$picStr;
        } else {
            $picArr = explode($delimiter, $picStr);
        }
        foreach ($picArr as $picName) {
            if (array_key_exists($picName, $cleanedImages)) {
                unset($cleanedImages[$picName]);
            }
        }
        self::saveCacheData($redisKey, $cleanedImages);
    }
}
