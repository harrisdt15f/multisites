<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/20/2019
 * Time: 9:35 PM
 */

namespace App\Models\Game\Lottery\Logics;


use App\Models\Game\Lottery\LotterySeriesMethod;
use App\Models\Game\Lottery\LotterySeriesWay;

trait LotteryBasicWayLogics
{
    /**
     * 检验是否中奖,返回中奖注数数组
     * @param  LotterySeriesWay  $oSeriesWay
     * @param  string  $sBetNumber
     * @param  null  $sPosition
     * @return array
     */
    public function checkPrize(LotterySeriesWay $oSeriesWay, $sBetNumber, $sPosition = null): array
    {
        $sBetNumber = str_replace('&', '', $sBetNumber);
        $aPrized = [];
        foreach ($oSeriesWay->WinningNumber as $iSeriesMethodId => $sWnNumber) {
            $oSeriesMethod = LotterySeriesMethod::find($iSeriesMethodId);
            $oBasicMethod = $oSeriesMethod->basicMethod;
            $oBasicMethod->sPosition = $sPosition;
            $prizeLevel = $oBasicMethod->prizeLevel;
            $iCount = $oBasicMethod->getPrizeCount($oSeriesWay, $this, $sWnNumber, $sBetNumber);
            if ($iCount) {
                $iLevel = $prizeLevel->level;
                $aPrized[$oSeriesMethod->basic_method_id][$iLevel] = $iCount;
            }
        }
        return $aPrized;
    }


}