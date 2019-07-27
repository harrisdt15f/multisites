<?php

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryBasicMethod;
use Illuminate\Support\Facades\Cache;

trait CronJobLogics
{
    /**
     * 插入cronJob数据
     * @param  array   $cron
     * @param  string  $lotteryName
     * @return array
     */
    public static function createCronJob($cron, $lotteryName): array
    {
        $remarks = $lotteryName . '->自动开奖任务';
        $cronJobData = [
            'command' => $cron['command'],
            'param' => $cron['param'],
            'schedule' => $cron['schedule'],
            'status' => $cron['status'],
            'remarks' => $remarks,
        ];
        $cronJobEloq = new self();
        $cronJobEloq->fill($cronJobData);
        $cronJobEloq->save();
        if ($cronJobEloq->errors()->messages()) {
            return ['success' => false, 'message' => $cronJobEloq->errors()->messages()];
        }
        return ['success' => true, 'data' => $cronJobEloq];
    }

    /**
     * 获取开启状态的cron_job
     * @return  array
     */
    public static function getOpenCronJob(): array
    {
        $cacheKey = 'open_cron_job';
        if (Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
        } else {
            $data = self::select('command', 'param', 'schedule')->where('status', 1)->get()->toArray();
            Cache::forever($cacheKey, $data);
        }
        return $data;
    }
}
