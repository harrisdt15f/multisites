<?php

namespace App\Models\DeveloperUsage\TaskScheduling\Logics;

use Illuminate\Support\Facades\Cache;

trait CronJobLogics
{
    /**
     * 插入cronJob数据
     * @param  array   $cron
     * @return array
     */
    public static function createCronJob($cron): array
    {
        $cronJobData = [
            'command' => $cron['command'],
            'param' => $cron['param'],
            'schedule' => $cron['schedule'],
            'status' => $cron['status'],
            'remarks' => $cron['remarks'],
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
