<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 9:31 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Lib\Game\DigitalNumber;
use App\Lib\Game\Math;
use App\Models\Game\Lottery\LotteryBasicWay;
use App\Models\Game\Lottery\LotterySeriesWay;
use Illuminate\Support\Str;

trait LotteryBasicMethodLogics
{

    protected $splitChar = '|';

    /**
     * 按offset来截取中奖号码
     * @param  string  $sFullWinningNumber
     * @param  int  $iOffset
     * @return array|string
     */
    public function getWnNumber($sFullWinningNumber, $iOffset)
    {
        $sWnNumber = '';
        switch ($this->series_code) {
            case 'ssc':
                $sWnNumber = substr($sFullWinningNumber, (int)$iOffset, $this->digital_count);
                break;
        }
        return $this->getWinningNumber($sWnNumber);
    }

    /**
     * 分析中奖号码
     * @param  string  $sWinningNumber
     * @return string | array
     */
    public function getWinningNumber($sWinningNumber)
    {
        switch ($this->wn_function) {
            //##################################################################
            /*
             * 时时彩系列
             * area
             * bsde
             * combin
             * contain
             * equal
             * interest
             * optionalcombin
             * optionalequal
             * special
             * sum_tail
             * tsbs
             * tsEqual
             */
            case 'area'://返回区间中奖号码
                $aDigitals = str_split($sWinningNumber);
                $aWnNumbers = [];
                foreach ($aDigitals as $i => $iDigital) {
                    $aWnNumbers[] = $i < $this->special_count ? floor($iDigital / 2) : $iDigital;
                }
                $result = implode($aWnNumbers);
                break;
            case 'bsde'://getWnNumberBsde 返回大小单双中奖号码
                $validNums = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];//@todo 有可能需要参考一下之前的 series 的 validnum
                $minBigNumber = (int)(count($validNums) / 2);
                $aDigitals = str_split($sWinningNumber);
                $aWnNumbers = [];
                foreach ($aDigitals as $i => $iDigital) {
                    $sNumberOfPosition = (int)($iDigital >= $minBigNumber); // 大小
                    $sNumberOfPosition .= $iDigital % 2 + 2; // 单双
                    $aWnNumbers[$i] = $sNumberOfPosition;
                }
                $result = implode('|', $aWnNumbers);
                break;
            case 'combin'://检查组选单式号码是否合法 getWnNumberCombin checkCombinValid
                $aDigitals = str_split($sWinningNumber);
                $aDigitalCount = array_count_values($aDigitals);
                $iMaxRepeatCount = max($aDigitalCount);
                $iMinRepeatCount = min($aDigitalCount);
                $iUniqueCount = count($aDigitalCount);
                if ($iUniqueCount == $this->unique_count && $iMaxRepeatCount == $this->max_repeat_time && $iMinRepeatCount == $this->min_repeat_time) {
                    sort($aDigitals);
                    $result = implode($aDigitals);
                } else {
                    $result = false;
                }
                break;
            case 'contain'://返回不定位中奖号码 getWnNumberContain
                $aDigitals = str_split($sWinningNumber);
                $aDigitalCount = array_count_values($aDigitals);
                $aUniqueDigitals = array_keys($aDigitalCount);
                $aWnNumber = [];
                if ($this->min_repeat_time) {
                    if (count($aDigitalCount) >= $this->choose_count && max($aDigitalCount) >= $this->min_repeat_time) {
                        foreach ($aDigitalCount as $iDigital => $iCount) {
                            $iCount < $this->min_repeat_time or $aWnNumber[] = $iDigital;
                        }
                    }
                } else {
                    (count($aDigitalCount) < $this->choose_count) or $aWnNumber = $aUniqueDigitals;
                }
                $result = $aWnNumber ?: false;
                break;
            case 'equal'://返回直选中奖号码 getWnNumberEqual
                if ($this->span !== null) {
                    $aDigitals = str_split($sWinningNumber);
                    $iSpan = max($aDigitals) - min($aDigitals);
                    if ($iSpan == $this->span) {
                        if ($this->min_span) {
                            $iDigitalCount = count($aDigitals);
                            $aSpan = [];
                            for ($i = 1; $i < $iDigitalCount; $aSpan[] = abs($aDigitals[$i] - $aDigitals[$i++ - 1])) {

                            }
                            $aDigitals[] = abs($aDigitals[0] - $aDigitals[$iDigitalCount - 1]);
                            min($aSpan) === $this->min_span or $sWinningNumber = '';
                        }
                    } else {
                        $sWinningNumber = '';
                    }
                }
                $result = $sWinningNumber;
                break;
            case 'interest'://getWnNumberInterest 返回趣味中奖号码
                $aDigitals = str_split($sWinningNumber);
                $aWnNumbers = [];
                foreach ($aDigitals as $i => $iDigital) {
                    $aWnNumbers[] = $i < $this->special_count ? (int)($iDigital > 4) : $iDigital;
                }
                $result = implode($aWnNumbers);
                break;
            case 'optionalcombin': //返回任选组选中奖号码 getWnNumberOptionalcombin
            case 'optionalequal': //返回任选的中奖号码数字 getWnNumberOptionalequal
            case 'tsbs': //返回二星大小的中奖号码 getWnNumberTsbs
            case 'tsEqual': //返回二星相等的中奖 getWnNumberTsEqual
                $result = $sWinningNumber;
                break;
            case 'special': //返回特殊中奖号码 getWnNumberSpecial
                $aWnDigitals = array_unique(str_split($sWinningNumber));
                $bWin = count($aWnDigitals) === $this->unique_count;
                if ($bWin && $this->unique_count === 3) {
                    $iSpan = max($aWnDigitals) - min($aWnDigitals);
                    if (!$bWin = $iSpan === $this->span) {
                        if ($iSpan === 9) {
                            rsort($aWnDigitals);
                            $iSpanAB = $aWnDigitals[0] - $aWnDigitals[1];
                            $iSpanBC = $aWnDigitals[1] - $aWnDigitals[2];
                            $iMinSpan = min($iSpanAB, $iSpanBC);
                            $bWin = $iMinSpan === $this->min_span;
                        }
                    }
                }
                $result = $bWin ? $this->fixed_number : false;
                break;
            case 'sum_tail': //返回和尾中奖号码  getWnNumberSumTail
                $result = array_sum(str_split($sWinningNumber)) % 10;
                break;
            default:
                $result = false;
            //#############################[时时彩系列结束]#####################################

        }
        return $result; //返回合适的计算中奖号码的方法
    }

//##################################################################

    public function getPrizeCount(LotterySeriesWay $oSeriesWay, LotteryBasicWay $oBasicWay, $sWnNumber, $sBetNumber)
    {
        $sFunction = 'prize'.$oBasicWay->function.ucfirst(Str::camel($this->wn_function));
        switch ($sFunction) {
            //ssc 系列开始
            /**
             * 返回组选单式的中奖注数
             */
            case 'prizeEnumCombin':
                /**
                 * 返回直选单式的中奖注数
                 */
            case 'prizeEnumEqual':
                /**
                 * 返回混合组选的中奖注数
                 */
            case 'prizeMixCombinCombin':
                $aBetNumbers = explode($this->splitChar, $sBetNumber);
                $aKeys = array_keys($aBetNumbers, $sWnNumber);
                $result = count($aKeys);
                break;
            /**
             * 返回直选组合的中奖注数
             */
            case 'prizeMultiSequencingEqual':
                /**
                 * 返回趣味玩法的中奖注数
                 */
            case 'prizeFunSeparatedConstitutedInterest':
                /**
                 * 返回区间玩法的中奖注数
                 */
            case 'prizeSectionalizedSeparatedConstitutedArea':
                /**
                 * 返回直选复式的中奖注数
                 */
            case 'prizeSeparatedConstitutedEqual':
                $aWnDigitals = str_split($sWnNumber);
                $p = [];
                foreach ($aWnDigitals as $iDigital) {
                    $p[] = '[\d]*'.$iDigital.'[\d]*';
                }
                $pattern = '/^'.implode('\|', $p).'$/';
                $result = (int)preg_match($pattern, $sBetNumber);
                break;
            /**
             * 计算单区型组选复式的中奖注数
             */
            case 'prizeConstitutedCombin':
                if ($this->max_repeat_time == 1) {
                    $aBetDigitals = str_split($sBetNumber);
                    $aWnDigitals = str_split($sWnNumber);
                    $aDiff = array_diff($aWnDigitals, $aBetDigitals);
                    $result = (int)empty($aDiff);
                } else {
                    $aBetNumber = explode($this->splitChar, $sBetNumber);
                    $aWnDigitals = array_count_values(str_split($sWnNumber));
                    $aWnMaxs = array_keys($aWnDigitals, $this->max_repeat_time);
                    $aWnMins = array_keys($aWnDigitals, $this->min_repeat_time);
                    $aDiffMax = array_diff($aWnMaxs, str_split($aBetNumber[0]));
                    $aDiffMin = isset($aBetNumber[1]) ? array_diff($aWnMins,
                        str_split($aBetNumber[1])) : array_diff($aWnMins, str_split($aBetNumber[0]));
                    $result = (int)(empty($aDiffMax) && empty($aDiffMin));
                }
                break;
            /**
             * 返回不定位的中奖注数
             */
            case 'prizeConstitutedContain':
                $aBetDigitals = array_unique(str_split($sBetNumber));
                $aBoth = array_intersect($sWnNumber, $aBetDigitals);
                $iHitCount = count($aBoth);
                $result = $iHitCount >= $this->choose_count ? Math::combin($iHitCount, $this->choose_count) : 0;
                break;
            /**
             * 返回大小单双的中奖注数
             */
            case 'prizeBigSmallOddEvenBsde':
                $aWnDigitals = explode($this->splitChar, $sWnNumber);
                $aBetDigitals = explode($this->splitChar, $sBetNumber);
                $iWonCount = 1;
                foreach ($aWnDigitals as $i => $sWnDigitals) {
                    $aWnDigitalsOfWei = str_split($sWnDigitals);
                    $aBetDigitalsOfWei = str_split($aBetDigitals[$i]);
                    $aBoth = array_intersect($aWnDigitalsOfWei, $aBetDigitalsOfWei);
                    if (!$iWonCount *= count($aBoth)) {
                        break;
                    }
                }
                $result = $iWonCount;
                break;
            /**
             * 计算双区型组选复式的中奖注数
             */
            case 'prizeConstitutedDoubleAreaCombin':
                /**
                 * 计算双区型组选复式的中奖注数
                 */
            case 'prizeConstitutedForCombin30Combin':
                $aBetNumber = explode($this->splitChar, $sBetNumber);
                $aWnDigitals = array_count_values(str_split($sWnNumber));
                $aWnMaxs = array_keys($aWnDigitals, $this->max_repeat_time);
                $aWnMins = array_keys($aWnDigitals, $this->min_repeat_time);
                $aDiffMax = array_diff($aWnMaxs, str_split($aBetNumber[0]));
                $aDiffMin = isset($aBetNumber[1]) ? array_diff($aWnMins,
                    str_split($aBetNumber[1])) : array_diff($aWnMins, str_split($aBetNumber[0]));
                $result = (int)(empty($aDiffMax) && empty($aDiffMin));
                break;
            /**
             * 返回和尾的中奖注数
             */
            case 'prizeSumTailSumTail'://prizeSumTailSum_tail
                $iSumTail = DigitalNumber::getSumTail($sWnNumber);
                $aBetNumbers = str_split($sBetNumber);
                $result = (int)in_array((string)$iSumTail, $aBetNumbers, true);
                break;
            /**
             * 返回三星特殊的中奖注数
             */
            case 'prizeSpecialConstitutedSpecial':
                $result = (int)preg_match("/$sWnNumber/", $sBetNumber);
                break;
            /**
             * 返回直选跨度的中奖注数
             */
            case 'prizeSpanEqual':
                $iSpan = DigitalNumber::getSpan($sWnNumber);
                $aBetNumbers = str_split($sBetNumber);
                $result = (int)in_array((string)$iSpan, $aBetNumbers, true);
                break;
            /**
             * 返回组选包胆的中奖注数
             */
            case 'prizeNecessaryCombin':
                $aWnDigitals = array_unique(str_split($sWnNumber));
                $result = (int)in_array($sBetNumber, $aWnDigitals, true);
                break;

            case 'prizeSumEqual':
                /**
                 * 返回直选和值的中奖注数
                 */
            case 'prizeSumCombin':
                /**
                 * 返回组选和值的中奖注数
                 */
                $iSum = DigitalNumber::getSum($sWnNumber);
                $aBetNumbers = explode($this->splitChar, $sBetNumber);
                $result = (int)in_array((string)$iSum, $aBetNumbers, true);
                break;
            case 'prizeMultiOneEqual':
                /**
                 * 返回定位胆的中奖注数
                 */
                $result = (int)preg_match("/$sWnNumber/", $sBetNumber);
                break;
            case 'prizeTwoStarBigSmallTsbs':
                $aBetNumber = str_split($sBetNumber);
                $intersect = array_intersect([0,1], $aBetNumber);
                $aBetNumber = array_unique($intersect);
                $iWnDigital = $this->getTsbslWinNumber($oSeriesWay->area_position, $sWnNumber);
                $result = (int) (in_array($iWnDigital, $aBetNumber));
                break;
            default:
                $result = 0;
        }
        return $result;
    }

    /**
     * 返回二星大小的中奖号码
     * @param $areaPosition
     * @param $sWnNumber
     * @return int
     */
    private function getTsbslWinNumber($areaPosition, $sWnNumber): ?int
    {
        $aWnNumber = str_split($sWnNumber);
        $aPosition = str_split($areaPosition);
        $aWnDigital = [];
        foreach ($aPosition as $iPosition) {
            $aWnDigital[] = $aWnNumber[$iPosition];
        }
        if($aWnDigital[0] > $aWnDigital[1]){
            return 0; //龙
        }elseif($aWnDigital[0] < $aWnDigital[1]){
            return 1; //虎
        }elseif($aWnDigital[0] === $aWnDigital[1]){
            return 2; //和
        }
    }
}