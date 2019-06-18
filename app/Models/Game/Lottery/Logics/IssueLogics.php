<?php

namespace App\Models\Game\Lottery\Logics;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/31/2019
 * Time: 5:24 PM
 */
trait IssueLogics
{
    /** =============== 功能函数 ============= */

    /**
     * 获取当前的奖期
     * @param  $lotteryId
     * @return mixed
     */
    public static function getCurrentIssue($lotteryId)
    {
        return self::where('lottery_id', $lotteryId)->where('end_time', '>', time())->orderBy('id', 'ASC')->first();
    }

    /**
     * 获取当前的奖期
     * @param  $lotteryId
     * @return mixed
     */
    public static function getNeedOpenIssue($lotteryId)
    {
        return self::where('lottery_id', $lotteryId)->where('allow_encode_time', '<', time())->where('status_encode',
            0)->orderBy('id', 'asc')->get();
    }

    /**
     * 获取所有的奖期
     * @param  $issueArr
     * @return void
     */
    public function getIssues($issueArr): void
    {
        if (is_array($issueArr)) {
            self::whereIn('issue', $issueArr)->get();
        }
    }

    /**
     * 获取所有可投奖期
     * @param  $lotteryId
     * @param  int         $count
     * @return mixed
     */
    public static function getCanBetIssue($lotteryId, $count = 50)
    {
        $time = time();
        return self::where('lottery_id', $lotteryId)->where('end_time', '>', $time)->orderBy('id',
            'ASC')->skip(0)->take($count)->get();
    }

    /**
     * 获取所有历史奖期
     * @param  $lotteryId
     * @param  int         $count
     * @return mixed
     */
    public static function getHistoryIssue($lotteryId, $count = 50)
    {
        $time = time();
        return self::where('lottery_id', $lotteryId)->where('begin_time', '<=', $time)->orderBy('id',
            'ASC')->skip(0)->take($count)->get();
    }

    /**
     * 获取上一期
     * @param  string  $lotterySign
     * @return mixed
     */
    public static function getLastIssue($lotterySign)
    {
        $time = time();
        return self::where('lottery_id', $lotterySign)->where('end_time', '<=', $time)->orderBy('id', 'DESC')->first();
    }
}
