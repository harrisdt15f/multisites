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
        $aPrized = [];
        foreach ($oSeriesWay->WinningNumber as $iSeriesMethodId => $sWnNumber) {
            $oSeriesMethod = LotterySeriesMethod::find($iSeriesMethodId);
            $oBasicMethod = $oSeriesMethod->basicMethod;
            $oBasicMethod->sPosition = $sPosition;
            $prizeLevel = $oBasicMethod->prizeLevel;
            $sBetNumber = $this->formatBetNumber($sBetNumber, $oSeriesMethod, $oSeriesWay, $sWnNumber);
            $iCount = $oBasicMethod->getPrizeCount($oSeriesWay, $this, $sWnNumber, $sBetNumber);
            $iLevel = $prizeLevel->level;
            $aPrized[$oSeriesMethod->basic_method_id][$iLevel] = $iCount;
        }
        return $aPrized;
    }

    public function formatBetNumber($sBetNumber, $oSeriesMethod, $oSeriesWay, $sWnNumber)
    {
        $sBetNumber = str_replace('&', '', $sBetNumber);
        $sSplitChar = '|';
        switch ($this->function) {
            case 'MultiOne':
            case 'LottoMultiOne':
                $aBetNumbers = explode($sSplitChar, $sBetNumber);
                $iOffset = $oSeriesMethod->offset >= 0 ? $oSeriesMethod->offset : $oSeriesMethod->offset + $oSeriesWay->digital_count;
                $sBetNumberFinal = $aBetNumbers[$iOffset];
                break;
            case 'MultiSequencing':
                $iWidthOfWnNumber = strlen($sWnNumber);
                $aBetNumbers = explode($sSplitChar, $sBetNumber);
                foreach ($aBetNumbers as $i => $tmp) {
                    if ($i < $oSeriesWay->digital_count - $iWidthOfWnNumber) {
                        unset($aBetNumbers[$i]);
                    }
                }
                $sBetNumberFinal = implode($sSplitChar, $aBetNumbers);
                break;
            default:
                $sBetNumberFinal = $sBetNumber;
        }
        return $sBetNumberFinal;
    }


}