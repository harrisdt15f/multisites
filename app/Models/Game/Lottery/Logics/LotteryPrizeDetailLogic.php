<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/15/2019
 * Time: 2:41 AM
 */

namespace App\Models\Game\Lottery\Logics;

trait LotteryPrizeDetailLogic
{
    public static function & getPrizeSetting($iGroupId, $iBasicMethodId): array
    {
        $prizeEloq = self::where('group_id', '=', $iGroupId)
            ->where('method_id', '=', $iBasicMethodId)
            ->withCacheCooldownSeconds(86400)
            ->get(['level', 'prize','series_code','full_prize']);
        $aPrize = [];
        foreach ($prizeEloq as $oPrizeDetail) {
            if (in_array($oPrizeDetail->series_code, self::$seriesFullFillAble, false)) {
                $aPrize[$oPrizeDetail->level] = 0.9 * $oPrizeDetail->full_prize;
            } else {
                $aPrize[$oPrizeDetail->level] = $oPrizeDetail->prize;
            }
        }
        return $aPrize;
    }
}
