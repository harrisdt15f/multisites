<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/20/2019
 * Time: 9:35 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryBasicMethod;
use App\Models\Game\Lottery\LotterySeriesMethod;
use App\Models\Game\Lottery\LotterySeriesWay;
use Illuminate\Support\Facades\Log;

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
            $iOffset = $oSeriesMethod->offset >= 0 ? $oSeriesMethod->offset : $oSeriesMethod->offset + $oSeriesWay->digital_count;
            $this->sPosition = $sPosition ?? $iOffset;
//            $oBasicMethod->sPosition = $sPosition;
            $sBetNumberFinal = $this->formatBetNumber($sBetNumber, $oSeriesMethod, $oSeriesWay, $sWnNumber);
            $iCount = $oBasicMethod->getPrizeCount($oSeriesWay, $this, $sWnNumber, $sBetNumberFinal);
            $iLevel = $this->getPrizeLevel($oSeriesWay, $oBasicMethod);
            $aPrized[$oSeriesMethod->basic_method_id][$iLevel] = $iCount;
        }
        return $aPrized;
    }

    /**
     * @param  LotterySeriesWay  $oSeriesWay
     * @param  LotteryBasicMethod  $oBasicMethod
     * @return mixed
     */
    public function getPrizeLevel(LotterySeriesWay $oSeriesWay, LotteryBasicMethod $oBasicMethod)
    {
        $arrWhere = [
            ['method_id', '=', $oSeriesWay->lottery_method_id],
            ['series_id', '=', $oSeriesWay->series_code],
        ];
        $prizeLevelQuery = $oBasicMethod->prizeLevel()->where($arrWhere);
        if ($this->sPosition !== null) {
//            $arrWhere[] = ['position', '=', (string)$this->sPosition];
            $positon = (string)$this->sPosition;
            $prizeLevelQuery->whereRaw('FIND_IN_SET('.$positon.',position)');
        }

        $prizeLevel = $prizeLevelQuery->first();
        if ($prizeLevel === null) {
            $errorString = 'PrizeLevel Query Null'.json_encode($oSeriesWay).'whereDatas are '.json_encode($arrWhere).'Query is '.$prizeLevelQuery->toSql();
            Log::channel('issues')->error($errorString);
        }
        return $prizeLevel->level;
    }

    public function formatBetNumber($sBetNumber, $oSeriesMethod, $oSeriesWay, $sWnNumber)
    {
        switch ($oSeriesWay->series_code) {
            case 'lotto';
                $sBetNumber = str_replace('&', ' ', $sBetNumber);
                break;
            default:
                $sBetNumber = str_replace('&', '', $sBetNumber);
                break;
        }
        $sSplitChar = '|';
        switch ($this->function) {
            case 'MultiOne':
            case 'LottoMultiOne':
                $aBetNumbers = explode($sSplitChar, $sBetNumber);
                $iOffset = $oSeriesMethod->offset >= 0 ? $oSeriesMethod->offset : $oSeriesMethod->offset + $oSeriesWay->digital_count;
                $this->sPosition = $iOffset;
                $sBetNumberFinal = $aBetNumbers[$iOffset];
                break;
            case 'LottoSingleOne':
                $iOffset = $oSeriesMethod->offset >= 0 ? $oSeriesMethod->offset : $oSeriesMethod->offset + $oSeriesWay->digital_count;
                $this->sPosition = $iOffset;
                $sBetNumberFinal = $sBetNumber;
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