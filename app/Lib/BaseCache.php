<?php namespace App\Lib;

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
        if ($cacheConfig['expire_time'] <= 0) {
            return Cache::forever($cacheConfig['key'], $value);
        } else {
            $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
            return Cache::put($cacheConfig['key'], $value, $expireTime);
        }
    }

    /**
     * 刷新缓存
     * @param $key
     * @return bool
     * @throws Exception
     */
    public static function mtsFlushCache($key): bool
    {
        $cacheConfig = self::getCacheConfig($key);
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
        return Cache::has($cacheConfig['key']);
    }
}
