<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/20/2019
 * Time: 9:35 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Lib\Game\DigitalNumber;
use App\Models\Game\Lottery\LotteryBasicMethod;
use App\Models\Game\Lottery\LotterySeriesMethod;
use App\Models\Game\Lottery\LotterySeriesWay;
use App\Models\Project;

trait LotteryBasicWayLogics
{
    private $iWidthOfWnNumber;
    private $aMultiples = [];

    /**
     * 检验是否中奖,返回中奖注数数组
     * @param  LotterySeriesWay  $oSeriesWay
     * @param  Project  $project
     * @param  null  $sPosition
     * @return array
     */
    public function checkPrize(LotterySeriesWay $oSeriesWay, Project $project, $sPosition = null): array
    {
        $sBetNumber = $project->bet_number;
        $aPrized = [];
        foreach ($oSeriesWay->WinningNumber as $iSeriesMethodId => $sWnNumber) {
            $oSeriesMethod = LotterySeriesMethod::find($iSeriesMethodId);
            $oBasicMethod = $oSeriesMethod->basicMethod;
//            $iOffset = $oSeriesMethod->offset >= 0 ?
//                $oSeriesMethod->offset : $oSeriesMethod->offset + $oSeriesWay->digital_count;
//            $this->sPosition = $sPosition ?? $iOffset;
//            $oBasicMethod->sPosition = $sPosition;
            $sBetNumberFinal = $this->formatBetNumber($sBetNumber, $oSeriesMethod, $oSeriesWay, $sWnNumber);
            $iCount = $oBasicMethod->getPrizeCount($oSeriesWay, $this, $sWnNumber, $sBetNumberFinal);
            $iLevel = $this->getPrizeLevel(
                $oSeriesWay,
                $oBasicMethod,
                $sWnNumber,
                $iCount,
                $sBetNumber,
                $sBetNumberFinal
            );
            if (isset($aPrized[$oSeriesMethod->basic_method_id])) {
                $aPrized[$oSeriesMethod->basic_method_id][$iLevel] += $iCount;
            } else {
                $aPrized[$oSeriesMethod->basic_method_id][$iLevel] = $iCount;
            }
        }
        return $aPrized;
    }

    public function getPrizeLevel(
        LotterySeriesWay $oSeriesWay,
        LotteryBasicMethod $oBasicMethod,
        $sWnNumber,
        &$iCount,
        $sBetNumber,
        $sBetNumberFinal
    ) {
        $aPrizeLevels = $oBasicMethod->getPrizeLevels();
        if ($this->function == 'MultiSequencing') {
            for ($i = 0; $i < $oSeriesWay->digital_count - $this->iWidthOfWnNumber; $i++) {//来自 formatbetnumber
                $iCount *= $this->aMultiples[$i];//来自 formatbetnumber
            }
        }
        if (($iLevel = count($aPrizeLevels)) > 1) {
            switch ($oBasicMethod->wn_function) {
                case 'k3contain':
                    $iLevelIndex = 0;
                    foreach ($aPrizeLevels as $sRule => $sLevel) {
                        if (false !== strpos($sWnNumber, (string)$sRule) &&
                            false !== strpos((string)$sRule, $sBetNumberFinal) &&
                            ($iLevelIndex === 0 || $sLevel < $iLevelIndex)
                        ) {
                            $iLevelIndex = $sLevel;
                        }
                    }
                    $iLevel = $iLevelIndex;
                    break;
                case 'TsSpecial':
                    $aWnNumber = explode(' ', $sWnNumber);
                    if (in_array($sBetNumber, ['0', '1'], false) && in_array('2', $aWnNumber, false)) {
                        $iLevel = 2;
                    } else {
                        $iLevel = 1;
                    }
                    break;
                case 'Pksumsum':
                    !is_array($sWnNumber) or $sWnNumber = implode($sWnNumber);
                    $iLevelIndex = intval($sWnNumber);
                    $iLevel = $aPrizeLevels[$iLevelIndex];
                    break;
                case 'Sixshengxiao':
                    if ($this->function == 'Sixliuxiao') {
                        $iLevel = 1;
                        if (in_array($sWnNumber, [01, 13, 25, 37, 49], false)) {
                            $iLevel = 2;
                        }
                    } else {
                        $iCountOfBetNumber = count(explode(',', $sBetNumberFinal));
                        if ($iCountOfBetNumber === 4) {
                            $iLevel = 1;
                        } else {
                            $iLevel = 2;
                        }
                    }
                    break;
                case 'Sixweishu':
                case 'Sixzongfen':
                case 'Sixzhixuan':
                    $iLevel = $aPrizeLevels[$sBetNumberFinal];
                    break;
                case 'Sixbuzhong':
                    $iLevel = $aPrizeLevels[$oSeriesWay->digital_count];
                    break;
                case 'Kl28Sum':
                    // 幸运28 游戏玩法，先获取中奖号码的奖金等级，未获取到 则是 串关
                    !is_array($sWnNumber) or $sWnNumber = implode($sWnNumber);
                    $iLevelIndex = $oBasicMethod->lottery_type == 2 ?
                        (int)$sWnNumber :
                        DigitalNumber::getSum($sWnNumber);
                    if (isset($aPrizeLevels[$iLevelIndex])) {
                        $iLevel = $aPrizeLevels[$iLevelIndex];
                    } else {
                        // 串关 赔率
                        $level2_wn_num = [0, 2, 4, 6, 8, 10, 12, 15, 17, 19, 21, 23, 25, 27];
                        $iLevel = 2;
                        if (in_array($iLevelIndex, $level2_wn_num, false)) {
                            $iLevel = 1;
                        }
                    }
                    break;
                default:
                    !is_array($sWnNumber) or $sWnNumber = implode($sWnNumber);
                    $iLevelIndex = $oBasicMethod->lottery_type == 2 ?
                        (int)$sWnNumber :
                        DigitalNumber::getSum($sWnNumber);
                    $iLevel = $aPrizeLevels[$iLevelIndex];
                    break;
            }
            //######################################
        }
        return $iLevel;
    }

    /**
     * @param $sBetNumber
     * @param $oSeriesMethod
     * @param  LotterySeriesWay  $oSeriesWay
     * @param $sWnNumber
     * @return mixed
     */
    /* public function getPrizeLevel(LotterySeriesWay $oSeriesWay, LotteryBasicMethod $oBasicMethod)
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
             $prizeLevelQuery = $oBasicMethod->prizeLevel()->where($arrWhere);
             $prizeLevelRowCount = $prizeLevelQuery->count();
             if ($prizeLevelRowCount === 1) { //只有一行奖金的时候就取一行
                 $prizeLevel = $prizeLevelQuery->first();
             } elseif ($prizeLevelRowCount < 1) { //没有奖金的时候
                 $errorString = 'PrizeLevel Query Null'.json_encode($oSeriesWay).
                 'whereDatas are '.json_encode($arrWhere).
                 'Query is '.$prizeLevelQuery->toSql();
                 Log::channel('issues')->error($errorString);
             } else {//有多条奖金时候
                 $errorString = 'PrizeLevel Query have Multiple Rows '.json_encode($oSeriesWay).
                 'whereDatas are '.json_encode($arrWhere).
                 'Query is '.$prizeLevelQuery->toSql();
                 $prizeDetailToDecide = $prizeLevelQuery->get()->toJson();
                 Log::channel('issues')->error($errorString);
                 Log::channel('issues')->error($prizeDetailToDecide);
             }
         }
         return $prizeLevel->level;
     }*/

    public function formatBetNumber($sBetNumber, $oSeriesMethod, $oSeriesWay, $sWnNumber)
    {
        switch ($oSeriesWay->series_code) {
            case 'lotto':
                $sBetNumber = str_replace('&', ' ', $sBetNumber);
                break;
            default:
                $sBetNumber = str_replace('&', '', $sBetNumber);
                break;
        }
        $sSplitChar = '|';
        switch ($this->function) {
            case 'MultiOne':
//            case 'LottoMultiOne'://已拆弹跟以前不一样了
                $aBetNumbers = explode($sSplitChar, $sBetNumber);
                $iOffset = $oSeriesMethod->offset >= 0 ?
                    $oSeriesMethod->offset : $oSeriesMethod->offset + $oSeriesWay->digital_count;
                $this->sPosition = $iOffset;
                $sBetNumberFinal = $aBetNumbers[$iOffset];
                break;
            case 'LottoSingleOne':
                $iOffset = $oSeriesMethod->offset >= 0 ?
                    $oSeriesMethod->offset : $oSeriesMethod->offset + $oSeriesWay->digital_count;
                $this->sPosition = $iOffset;
                $sBetNumberFinal = $sBetNumber;
                break;
            case 'MultiSequencing':
                $this->iWidthOfWnNumber = strlen($sWnNumber);
                $aBetNumbers = explode($sSplitChar, $sBetNumber);
                foreach ($aBetNumbers as $i => $betNumberItem) {
                    $this->aMultiples[$i] = strlen($betNumberItem);
                    if ($i < $oSeriesWay->digital_count - $this->iWidthOfWnNumber) {
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
