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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait LotteryBasicMethodLogics
{

    protected $splitChar = '|';

    protected $splitCharInArea = ' ';

    /**
     * 按offset来截取中奖号码
     * @param  string  $sFullWinningNumber
     * @param  int  $iOffset
     * @return array|string
     */
    public function getWnNumber($sFullWinningNumber, $iOffset)
    {
        $sWnNumber = '';
        $sFunction = '';
        switch ($this->series_code) {
            case 'ssc':
            case 'k3':
                $sWnNumber = substr($sFullWinningNumber, (int)$iOffset, $this->digital_count);
                $sFunction = 'getWinningNumber'.ucfirst($this->series_code);
                break;
            case 'lotto':
                $aBalls = explode($this->splitCharInArea, $sFullWinningNumber);
                $aNeedBalls = [];
                $i = $iOffset;
                for ($j = 0; $j < $this->digital_count; $j++) {
                    $aNeedBalls[$j] = $aBalls[$i];
                    $i++;
                }
                $sWnNumber = implode($this->splitCharInArea, $aNeedBalls);
                $sFunction = 'getWinningNumber'.ucfirst($this->series_code);
                break;
        }
        return $sFunction === '' ? false : $this->$sFunction($sWnNumber);
    }

    /**
     * 分析中奖号码
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
     * @param  string  $sWinningNumber
     * @return string | array
     */
    public function getWinningNumberSsc($sWinningNumber)
    {
        //#############################[时时彩系]#####################################
        switch ($this->wn_function) {
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

    /**
     * 分析中奖号码
     * 十一选五乐透彩系
     * LottoContain
     * LottoCombin
     * LottoEqual
     * LottoOddEven
     * LottoMiddle
     * @param  string  $sWinningNumber
     * @return string | array
     */
    public function getWinningNumberLotto($sWinningNumber)
    {
        //#############################[十一选五乐透系列]#####################################
        switch ($this->wn_function) {
            case 'LottoContain': //getWnNumberLottoContain 11选5不定位中奖号码
                $aDigitals = explode($this->splitCharInArea, $sWinningNumber);
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
            case 'LottoCombin'://getWnNumberLottoCombin 任选六中五
            case 'LottoEqual'://getWnNumberLottoEqual 定位胆
                $result = $sWinningNumber;
                break;
            case 'LottoOddEven'://getWnNumberLottoOddEven 返回定单双
                $aBalls = explode($this->splitCharInArea, $sWinningNumber);
                $iOddCount = 0;
                foreach ($aBalls as $iBall) {
                    $iOddCount += $iBall % 2;
                }
                $result = $iOddCount;
                break;
            case 'LottoMiddle'://getWnNumberLottoMiddle 猜中位
                $aBalls = explode($this->splitCharInArea, $sWinningNumber);
                sort($aBalls);
                $result = $aBalls[2];
                break;
            default:
                $result = false;
            //#############################[十一选五乐透系列结束]#####################################
        }
        return $result; //返回合适的计算中奖号码的方法
    }

    /**
     * 分析中奖号码
     * 十一选五乐透彩系
     * BigSmallOddEven
     * Enum
     * @param  string  $sWinningNumber
     * @return string | array
     */
    public function getWinningNumberK3($sWinningNumber)
    {
        //#############################[k3系列开始]#####################################
        switch ($this->wn_function) {
            case 'k3bsde': //getWnNumberK3bsde 快3大小单双的中奖号
                $iSum = DigitalNumber::getSum($sWinningNumber);
                if ($iSum < 3 || $iSum > 18) {
                    return '';
                }
                $sWnNumber = (int)($iSum >= 11); // 大小
                $sWnNumber .= $iSum % 2 + 2; // 单双
                $result = $sWnNumber;
                break;
            case 'k3combin'://getWnNumberK3combin 快3组选的中奖号
                $sWinningNumber = str_split($sWinningNumber, 1);
                sort($sWinningNumber);
                $sWinningNumber = implode($sWinningNumber);
                $result = $this->checkSpan($sWinningNumber) ? $sWinningNumber : '';
                break;
            case 'k3contain'://getWnNumberLottoEqual 定位胆
                $sWinningNumber = str_split($sWinningNumber, 1);
                sort($sWinningNumber);
                $result = implode($sWinningNumber);
                break;
            default:
                $result = false;
            //#############################[k3系列结束]#####################################
        }
        return $result; //返回合适的计算中奖号码的方法
    }

    //##################################################################

    /**
     * @param  LotterySeriesWay  $oSeriesWay
     * @param  LotteryBasicWay  $oBasicWay
     * @param $sWnNumber
     * @param $sBetNumber
     * @return float|int
     */
    public function getPrizeCount(LotterySeriesWay $oSeriesWay, LotteryBasicWay $oBasicWay, $sWnNumber, $sBetNumber)
    {
        $pFunction = 'getPrize'.ucfirst($this->series_code);
        $sFunction = 'prize'.$oBasicWay->function.ucfirst(Str::camel($this->wn_function));
        return $this->$pFunction($sFunction, $sBetNumber, $sWnNumber, $oSeriesWay);
    }

    //##########################################################[时时彩系列 prize 计算]#########################################

    /**
     * ssc 系列
     * @param $sFunction
     * @param $sBetNumber
     * @param $sWnNumber
     * @param  LotterySeriesWay  $oSeriesWay
     * @return float|int
     */
    private function getPrizeSsc($sFunction, $sBetNumber, $sWnNumber, LotterySeriesWay $oSeriesWay)
    {
        switch ($sFunction) {
            case 'prizeEnumCombin'://返回组选单式的中奖注数
            case 'prizeEnumEqual'://返回直选单式的中奖注数
            case 'prizeMixCombinCombin'://返回混合组选的中奖注数
                $aBetNumbers = explode($this->splitChar, $sBetNumber);
                $aKeys = array_keys($aBetNumbers, $sWnNumber);
                $result = count($aKeys);
                break;
            case 'prizeMultiSequencingEqual'://返回直选组合的中奖注数
            case 'prizeFunSeparatedConstitutedInterest'://返回趣味玩法的中奖注数
            case 'prizeSectionalizedSeparatedConstitutedArea'://返回区间玩法的中奖注数
            case 'prizeSeparatedConstitutedEqual'://返回直选复式的中奖注数
                $aWnDigitals = str_split($sWnNumber);
                $p = [];
                foreach ($aWnDigitals as $iDigital) {
                    $p[] = '[\d]*'.$iDigital.'[\d]*';
                }
                $pattern = '/^'.implode('\|', $p).'$/';
                $result = (int)preg_match($pattern, $sBetNumber);
                break;
            case 'prizeConstitutedCombin'://计算单区型组选复式的中奖注数
                if ($this->max_repeat_time === 1) {
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
            case 'prizeConstitutedContain'://返回不定位的中奖注数
                $aBetDigitals = array_unique(str_split($sBetNumber));
                $aBoth = array_intersect($sWnNumber, $aBetDigitals);
                $iHitCount = count($aBoth);
                $result = $iHitCount >= $this->choose_count ? Math::combin($iHitCount, $this->choose_count) : 0;
                break;
            case 'prizeBigSmallOddEvenBsde'://返回大小单双的中奖注数
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
            case 'prizeConstitutedDoubleAreaCombin'://计算双区型组选复式的中奖注数
            case 'prizeConstitutedForCombin30Combin'://计算双区型组选复式的中奖注数
                $aBetNumber = explode($this->splitChar, $sBetNumber);
                $aWnDigitals = array_count_values(str_split($sWnNumber));
                $aWnMaxs = array_keys($aWnDigitals, $this->max_repeat_time);
                $aWnMins = array_keys($aWnDigitals, $this->min_repeat_time);
                $aDiffMax = array_diff($aWnMaxs, str_split($aBetNumber[0]));
                $aDiffMin = isset($aBetNumber[1]) ? array_diff($aWnMins,
                    str_split($aBetNumber[1])) : array_diff($aWnMins, str_split($aBetNumber[0]));
                $result = (int)(empty($aDiffMax) && empty($aDiffMin));
                break;
            case 'prizeSumTailSumTail'://prizeSumTailSum_tail 返回和尾的中奖注数
                $iSumTail = DigitalNumber::getSumTail($sWnNumber);
                $aBetNumbers = str_split($sBetNumber);
                $result = (int)in_array((string)$iSumTail, $aBetNumbers, true);
                break;
            case 'prizeSpecialConstitutedSpecial'://返回三星特殊的中奖注数
                $result = (int)preg_match("/$sWnNumber/", $sBetNumber);
                break;
            case 'prizeSpanEqual'://返回直选跨度的中奖注数
                $iSpan = DigitalNumber::getSpan($sWnNumber);
                $aBetNumbers = str_split($sBetNumber);
                $result = (int)in_array((string)$iSpan, $aBetNumbers, true);
                break;
            case 'prizeNecessaryCombin'://返回组选包胆的中奖注数
                $aWnDigitals = array_unique(str_split($sWnNumber));
                $result = (int)in_array($sBetNumber, $aWnDigitals, true);
                break;
            case 'prizeSumEqual'://返回直选和值的中奖注数
            case 'prizeSumCombin'://返回组选和值的中奖注数
                $iSum = DigitalNumber::getSum($sWnNumber);
                $aBetNumbers = explode($this->splitChar, $sBetNumber);
                $result = (int)in_array((string)$iSum, $aBetNumbers, true);
                break;
            case 'prizeMultiOneEqual'://返回定位胆的中奖注数
                $result = (int)preg_match("/$sWnNumber/", $sBetNumber);
                break;
            case 'prizeTwoStarBigSmallTsbs':
            case 'prizeTwoStarBigSmallTsEqual':
            case 'commonPrizeTwoStar': //龙虎和 共用函数
                $arrToIntersectWith = $this->wn_function == 'tsEqual' ? [2] : [0, 1];
                $aBetNumber = str_split($sBetNumber);
                $intersect = array_intersect($arrToIntersectWith, $aBetNumber);
                $aBetNumber = array_unique($intersect);
                $iWnDigital = $this->getTsbslWinNumber($oSeriesWay->area_position, $sWnNumber);
                $result = (int)in_array($iWnDigital, $aBetNumber, true);
                break;
            default:
                Log::channel('issues')->info('需要添加时时彩系列方法:'.$sFunction.$oSeriesWay->toJson());
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
        if ($aWnDigital[0] > $aWnDigital[1]) {
            return 0; //龙
        } elseif ($aWnDigital[0] < $aWnDigital[1]) {
            return 1; //虎
        } elseif ($aWnDigital[0] === $aWnDigital[1]) {
            return 2; //和
        }
    }

    //##########################################################[十一选五乐透系列 prize 计算]#########################################

    /**
     * 十一选五系列计算中奖
     * prizeLottoEqualLottoContain
     * prizeLottoEqualLottoCombin
     * prizeLottoEqualLottoEqual
     * prizeLottoConstitutedLottoContain
     * prizeLottoConstitutedLottoCombin
     * prizeLottoMultiOneLottoEqual
     * prizeLottoConstitutedLottoOddEven
     * prizeLottoConstitutedLottoMiddle
     * prizeLottoSeparatedConstitutedLottoEqual
     * prizeLottoNecessaryConstitutedLottoContain
     * prizeLottoNecessaryConstitutedLottoCombin
     * @param $sFunction
     * @param $sBetNumber
     * @param $sWnNumber
     * @param  LotterySeriesWay  $oSeriesWay
     * @return float|int
     */
    private function getPrizeLotto($sFunction, $sBetNumber, $sWnNumber, LotterySeriesWay $oSeriesWay)
    {

        switch ($sFunction) {
            case 'prizeLottoEqualLottoContain'://计算11选5任选1至任选5单式的中奖注数
                sort($sWnNumber);
                $aBets = explode($this->splitChar, $sBetNumber);
                $iCount = 0;
                foreach ($aBets as $sBet) {
                    $aBetBalls = explode($this->splitCharInArea, $sBet);
                    $aHits = array_intersect($aBetBalls, $sWnNumber);
                    $iCount += (int)(count($aHits) === $this->choose_count);
                }
                $result = $iCount;
                break;
            case 'prizeLottoEqualLottoCombin'://计算11选5任选6中五至任选8中5单式和组选单式的中奖注数
                $aWnBalls = explode($this->splitCharInArea, $sWnNumber);
                $aBets = explode($this->splitChar, $sBetNumber);
                $iCount = 0;
                foreach ($aBets as $sBet) {
                    $aTmpBalls = explode($this->splitCharInArea, $sBet);
                    $aHitBalls = array_intersect($aTmpBalls, $aWnBalls);
                    if ($bWon = (count($aHitBalls) === $this->wn_length)) {
                        $iCount++;
                    }
                }
                $result = $iCount;
                break;
            case 'prizeLottoEqualLottoEqual'://计算11选5直选单式的中奖注数
                $aBets = explode($this->splitChar, $sBetNumber);
                $result = (int)in_array($sWnNumber, $aBets, false);
                break;
            case 'prizeLottoConstitutedLottoContain': //计算11选5任选一至五复式的中奖注数
                $iHitCount = $this->_getHitNumbersOfLotto($sBetNumber, $sWnNumber, $iBetBallCount);
                $result = Math::combin($iHitCount, $this->choose_count);
                break;
            case 'prizeLottoConstitutedLottoCombin'://计算11选5任选五至八复式的中奖注数
                $iHitCount = $this->_getHitNumbersOfLotto($sBetNumber, $sWnNumber, $iBetBallCount);
                if ($iHitCount < $this->wn_length) {
                    $result = 0;
                    break;
                }
                $iNeedOtherBallCount = $this->buy_length - $this->wn_length;
                $iUnHitCount = $iBetBallCount - $iHitCount;
                $result = Math::combin($iUnHitCount, $iNeedOtherBallCount);
                break;
            case 'prizeLottoMultiOneLottoEqual': //计算11选5定位胆的中奖注数
            case 'prizeLottoConstitutedLottoOddEven'://计算11选5定单双的中奖数字
            case 'prizeLottoConstitutedLottoMiddle'://11选5猜中位的中奖注数
                $aBetBalls = explode($this->splitCharInArea, $sBetNumber);
                $result = (int)in_array($sWnNumber, $aBetBalls, false);
                break;
            case 'prizeLottoSeparatedConstitutedLottoEqual'://计算11选5任选五至八复式的中奖注数
                $aWnBalls = explode($this->splitCharInArea, $sWnNumber);
                $aBetBalls = explode($this->splitChar, $sBetNumber);
                $iHitPosCount = 0;
                if (count($aWnBalls) !== count($aBetBalls)) {
                    $result = 0;
                    break;
                }
                foreach ($aBetBalls as $i => $sBetNumberOfPos) {
                    $aBetBallsOfPos = explode($this->splitCharInArea, $sBetNumberOfPos);
                    if (!in_array($aWnBalls[$i], $aBetBallsOfPos, false)) {
                        break;
                    }
                    $iHitPosCount++;
                }
                $result = (int)($iHitPosCount === $this->wn_length);
                break;
            case 'prizeLottoNecessaryConstitutedLottoContain':
                [$sBetNecessaried, $sBetConstituted] = explode($this->splitChar, $sBetNumber);
                $aBetNecessaried = explode($this->splitCharInArea, $sBetNecessaried);
                $aHitNecessaried = array_intersect($aBetNecessaried, $sWnNumber);
                $iHitNessariedCount = count($aHitNecessaried);
                if ($iHitNessariedCount !== count($aBetNecessaried)) {
                    $result = 0;
                    break;
                }
                $iNeedOfNecessariedCount = $this->wn_length - $iHitNessariedCount;
                if ($iNeedOfNecessariedCount === 0) {
                    $result = 1;
                    break;
                }
                $aBetConstituted = explode($this->splitCharInArea, $sBetConstituted);
                $aHitConstituted = array_intersect($aBetConstituted, $sWnNumber);
                $iHitConstitutedCount = count($aHitConstituted);
                if ($iHitConstitutedCount < $iNeedOfNecessariedCount) {
                    $result = 0;
                    break;
                }
                $result = Math::combin($iHitConstitutedCount, $iNeedOfNecessariedCount);
                break;
            case 'prizeLottoNecessaryConstitutedLottoCombin':
                $aWnNumber = explode($this->splitCharInArea, $sWnNumber);
                [$sBetNecessaried, $sBetConstituted] = explode($this->splitChar, $sBetNumber);
                $aBetNecessaried = explode($this->splitCharInArea, $sBetNecessaried);
                $aHitNecessaried = array_intersect($aBetNecessaried, $aWnNumber);
                $iBetNecessariedCount = count($aBetNecessaried);
                $iHitNessariedCount = count($aHitNecessaried);
                $iNeedOfBetBallsCount = $this->buy_length - $iBetNecessariedCount;// 凑足一注投注码还需要的复式码个数
                $aBetConstituted = explode($this->splitCharInArea, $sBetConstituted);
                $iBetConstitutedCount = count($aBetConstituted);
                if ($iNeedOfBetBallsCount > $iBetConstitutedCount) {// 如果复式码个数不足, 则不中奖
                    $result = 0;
                    break;
                }
                $aHitConstituted = array_intersect($aBetConstituted, $aWnNumber);// 求出中得的复式码个数
                $iHitConstitutedCount = count($aHitConstituted);
                if ($iBetNecessariedCount + $iHitConstitutedCount > $this->buy_length) {// 如果胆码个数+中得的复式码个数,则不中奖
                    $result = 0;
                    break;
                }
                $iNonHitConstitutedCount = $iBetConstitutedCount - $iHitConstitutedCount;// 求出未中得的复式码个数
                if ($iHitConstitutedCount + $iHitNessariedCount < $this->wn_length) {
                    $result = 0;
                    break;
                }
                $iNeedNonHitCount = $iNeedOfBetBallsCount - $iHitConstitutedCount;
                $result = Math::combin($iNonHitConstitutedCount, $iNeedNonHitCount);
                break;
            default:
                Log::channel('issues')->info('需要添加十一选五系列方法:'.$sFunction.$oSeriesWay->toJson());
                $result = 0;
        }
        return $result;
    }

    /**
     * @param $sBetNumber
     * @param $sWnNumber
     * @param $iBetBallCount
     * @return int
     */
    private function _getHitNumbersOfLotto($sBetNumber, $sWnNumber, & $iBetBallCount): int
    {
        $aWnBalls = is_array($sWnNumber) ? $sWnNumber : explode($this->splitCharInArea, $sWnNumber);
        $aBetBalls = explode($this->splitCharInArea, $sBetNumber);
        $iBetBallCount = count($aBetBalls);
        return count(array_intersect($aBetBalls, $aWnBalls));
    }
//##########################################################[k3系列 prize 计算]#########################################

    /**
     * 十一选五系列计算中奖
     * prizeBigSmallOddEvenK3bsde
     * prizeEnumK3combin
     * prizeEnumK3contain
     * prizeSumK3combin
     * @param $sFunction
     * @param $sBetNumber
     * @param $sWnNumber
     * @param  LotterySeriesWay  $oSeriesWay
     * @return float|int
     */
    private function getPrizeK3($sFunction, $sBetNumber, $sWnNumber, LotterySeriesWay $oSeriesWay)
    {

        switch ($sFunction) {
            case 'prizeBigSmallOddEvenK3bsde'://K3大小单双和值的中奖注数
                $aWnNumber = str_split($sWnNumber);
                $aBetNumber = str_split($sBetNumber);
                $aBoth = array_intersect($aWnNumber, $aBetNumber);
                $result = count($aBoth);
                break;
            case 'prizeEnumK3combin'://快3组选单式的中奖注数
                $aBetNumbers = explode($this->splitChar, $sBetNumber);
                $result = (int)array_keys($aBetNumbers, $sWnNumber);
                break;
            case 'prizeEnumK3contain'://返回快3任选单式的中奖注数
                $winCount = 0;
                $aBetNumbers = explode($this->splitChar, $sBetNumber);
                $aWnNumber = str_split($sWnNumber);
                $aCombinations = Math::getCombinationToString($aWnNumber, $this->choose_count);
                $aDigitals = [];
                foreach ($aCombinations as $sCombination) {
                    $aDigital = explode(',', $sCombination);
                    sort($aDigital);
                    $aDigitals[] = implode($aDigital);
                }
                foreach ($aBetNumbers as $ithemBetNumber) {
                    if (in_array($ithemBetNumber, $aDigitals, false)) {
                        $winCount++;
                    }
                }
                $result = $winCount;
                break;
            case 'prizeSumK3combin'://K3组选和值的中奖注数
                $iSum = DigitalNumber::getSum($sWnNumber);
                $aBetNumbers = explode($this->splitChar, $sBetNumber);
                $result = (int)in_array($iSum, $aBetNumbers, false);
                break;
            default:
                Log::channel('issues')->info('需要添加k3系列方法:'.$sFunction.$oSeriesWay->toJson());
                $result = 0;
        }
        return $result;
    }

    /**
     * 检查跨度是否合法
     * @param $sNumber
     * @return bool
     */
    public function checkSpan(& $sNumber): bool
    {
        if (!is_null($this->span)) {
            $aDigitals = str_split($sNumber, 1);
            if ($this->min_span && (max($aDigitals) - min($aDigitals)) == $this->span) {
                $aSpan = [];
                for ($i = 1, $iMax = count($aDigitals); $i < $iMax; $aSpan[] = abs($aDigitals[$i] - $aDigitals[$i++ - 1])) {
                }
                min($aSpan) == $this->min_span or $sNumber = '';
            } else {
                $sNumber = '';
            }
        }
        return $sNumber ? true : false;
    }
}