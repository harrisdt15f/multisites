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

    public static function getGenerateIssueTime()
    {
        $redisKey = 'generateIssueTime';
        if (Cache::has($redisKey)) {
            $generateIssueTime = Cache::get($redisKey);
        } else {
            $generateIssueTime = configure('generate_issue_time');
            Cache::forever($redisKey, $generateIssueTime);
        }
        return $generateIssueTime;
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

    public static function getBetPrizeGroup($update = 0)
    {
        $redisKey = 'bet_prize_group';
        $data = [];
        if (Cache::has($redisKey) && $update === 0) {
            $data = Cache::get($redisKey);
        } else {
            $signArr = ['min_bet_prize_group', 'max_bet_prize_group'];
            $BetPrizeGroupEloq = self::whereIn('sign', $signArr)->get();
            foreach ($BetPrizeGroupEloq as $item) {
                $data[$item->sign] = $item->value;
            }
            Cache::forever($redisKey, $data);
        }
        return $data;
    }

    /**
     * 更新该配置有关的缓存
     * @param  string $sign
     * @param  string $value
     * @return void
     */
    public static function updateConfigCache($sign, $value): void
    {
        //相关奖金组的缓存更新
        if ($sign = 'min_bet_prize_group' || $sign = 'max_bet_prize_group') {
            self::getBetPrizeGroup(1);
        }
    }
}
