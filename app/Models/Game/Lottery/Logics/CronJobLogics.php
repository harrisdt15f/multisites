<?php

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryBasicMethod;

trait CronJobLogics
{
    /**
     * 插入cronJob数据
     * @param  int     $lotteryId
     * @param  string  $lotterySign
     * @param  array   $cron
     * @return array
     */
    public static function createCronJob($lotteryId, $lotterySign, $cron): array
    {
        $cronJobParamArr = ['--lottery_sign' => $lotterySign];
        $cronJobData = [
            'command' => self::COMMAND,
            'lottery_id' => $lotteryId,
            'param' => json_encode($cronJobParamArr),
            'schedule' => $cron['schedule'],
            'status' => $cron['status'],
        ];
        $cronJobEloq = new self;
        $cronJobEloq->fill($cronJobData);
        $cronJobEloq->save();
        if ($cronJobEloq->errors()->messages()) {
            return ['success' => false, 'message' => $cronJobEloq->errors()->messages()];
        }
        return ['success' => true];
    }

    public static function getOpenCronJob()
    {
        return self::where('status', 1)->get();
    }
}
