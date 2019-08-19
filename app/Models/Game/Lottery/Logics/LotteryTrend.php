<?php

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\Logics\LotteryTrend\LotteryTrendSsc;

trait LotteryTrend
{
    /**
     * 彩种趋势记录
     * @param LotteryIssue $lotteryIssue
     * @return bool
     */
    public static function trend(LotteryIssue $lotteryIssue)
    {
        $lotterySeriesName = $lotteryIssue->lottery->series_id;
        $issue = $lotteryIssue->issue;
        $officialCode = $lotteryIssue->official_code;

        if (!$issue || !$officialCode) {
            return false;
        }
        

        switch ($lotterySeriesName) {
            case 'ssc':
                LotteryTrendSsc::trend($issue, $officialCode);
                break;
            default:
                return false;
                break;
        }

        return true;
    }
}
