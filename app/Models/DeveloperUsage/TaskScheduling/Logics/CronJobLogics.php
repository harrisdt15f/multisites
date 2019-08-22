<?php

namespace App\Models\DeveloperUsage\TaskScheduling\Logics;

use App\Lib\Common\CacheRelated;
use Illuminate\Support\Facades\Cache;

trait CronJobLogics
{
    /**
     * 插入cronJob数据
     * @param  array   $cronData
     * @return array
     */
    public static function insertCronJob($cronData): array
    {
        if ($cronData['status'] == self::STATUS_OPEN) {
            $checkSchedule = self::checkSchedule($cronData['schedule']); //状态为开启时，cron表达式不是5位，强制关闭这条定时任务
            if ($checkSchedule === false) {
                $cronData['status'] = self::STATUS_CLOSE;
            }
        }
        $cronJobData = [
            'command' => $cronData['command'],
            'param' => $cronData['param'],
            'schedule' => $cronData['schedule'],
            'status' => $cronData['status'],
            'remarks' => $cronData['remarks'],
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
        $tags = 'job';
        $cacheKey = 'open_cron_job';
        $data = CacheRelated::getTagsCache($tags, $cacheKey);
        if ($data === false) {
            $cronJobELoq = self::select('command', 'param', 'schedule')->where('status', self::STATUS_OPEN)->get();
            if ($cronJobELoq->count() !== 0) {
                $data = $cronJobELoq->toArray();
                CacheRelated::setTagsCache($tags, $cacheKey, $data);
            }
        }
        return $data;
    }

    public static function updateOPenCronJob()
    {
        $tags = 'job';
        $cacheKey = 'open_cron_job';
        $cronJobELoq = self::select('command', 'param', 'schedule')->where('status', self::STATUS_OPEN)->get();
        if ($cronJobELoq->count() !== 0) {
            $data = $cronJobELoq->toArray();
            CacheRelated::setTagsCache($tags, $cacheKey, $data);
        }
    }
    
    /**
     * 获取所有的的cron_job
     * @return  array
     */
    public static function getAllCronJob(): array
    {
        $data = self::select('id', 'command', 'param', 'schedule', 'status', 'remarks')->get()->toArray();
        return $data;
    }

    //检查cron表达式是否合法
    public static function checkSchedule($schedule): bool
    {
        $scheduleArr = explode(' ', $schedule);
        $count = count($scheduleArr);
        if ($count !== 5) {
            return false;
        }
        return true;
    }
}
