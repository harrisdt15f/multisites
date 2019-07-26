<?php

namespace App\Models\Game\Lottery\Logics;

use Illuminate\Support\Facades\Cache;

trait LotterySerieLogics
{
    public static function getList()
    {
        $cacheKey = 'lotterySerieList';
        if (Cache::has($cacheKey)) {
            $listData = Cache::get($cacheKey);
        } else {
            $listData = [];
            $serieEloq = self::select('series_name', 'title', 'status', 'encode_splitter')->get();
            foreach ($serieEloq as $key => $serieItem) {
                $listData[$serieItem->series_name]['series_name'] = $serieItem->series_name;
                $listData[$serieItem->series_name]['title'] = $serieItem->title;
                $listData[$serieItem->series_name]['status'] = $serieItem->status;
                $listData[$serieItem->series_name]['encode_splitter'] = $serieItem->encode_splitter;
            }
            Cache::forever($cacheKey, $listData);
        }
        return $listData;
    }
}
