<?php

namespace App\Models\DeveloperUsage\MethodLevel\Traits;

use App\Lib\Common\CacheRelated;

trait MethodLevelLogics
{

    /**
     * 后台玩法等级列表缓存
     * @param  integer $update
     * @return array
     */
    public static function methodLevelDetail($update = 0): array
    {
        $tags = 'lottery';
        $redisKey = 'lottery_method_leve_detail';
        $data = false;
        if ($update === 0) {
            $data = CacheRelated::getTagsCache($tags, $redisKey);
        }
        if ($data === false) {
            $methodtype = self::groupBy('method_id')->orderBy('id', 'asc')->get();
            $data = [];
            foreach ($methodtype as $method) {
                $data[$method->method_id] = self::select('id', 'method_id', 'series_id', 'level', 'position', 'count', 'prize')
                    ->where('method_id', $method->method_id)
                    ->get()
                    ->toArray();
            }
            CacheRelated::setTagsCache($tags, $redisKey, $data);
        }
        return $data;
    }
}
