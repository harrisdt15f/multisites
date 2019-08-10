<?php

namespace App\Models\Game\Lottery\Logics;

use Illuminate\Support\Facades\Cache;

trait LotterySerieLogics
{
    /**
     * 获取彩种系列缓存
     * @return array
     */
    public static function getList(): array
    {
        $cacheKey = self::getCacheKey();
        if (Cache::has($cacheKey)) {
            $listData = Cache::get($cacheKey);
        } else {
            $listData = self::updateSerieCache();
        }
        return $listData;
    }

    /**
     * 更新彩种系列缓存
     * @return array
     */
    public static function updateSerieCache(): array
    {
        $cacheKey = self::getCacheKey();
        $listData = [];
        $serieEloq = self::select('series_name', 'title', 'status', 'encode_splitter')->get();
        foreach ($serieEloq as $key => $serieItem) {
            $listData[$serieItem->series_name]['series_name'] = $serieItem->series_name;
            $listData[$serieItem->series_name]['title'] = $serieItem->title;
            $listData[$serieItem->series_name]['status'] = $serieItem->status;
            $listData[$serieItem->series_name]['encode_splitter'] = $serieItem->encode_splitter;
        }
        Cache::forever($cacheKey, $listData);
        return $listData;
    }

    /**
     * 获取彩种系列缓存key
     * @return string
     */
    public static function getCacheKey(): string
    {
        return 'lotterySerieList';
    }
}
