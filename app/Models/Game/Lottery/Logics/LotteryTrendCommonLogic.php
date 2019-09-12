<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 9/11/2019
 * Time: 1:39 AM
 */

namespace App\Models\Game\Lottery\Logics;

trait LotteryTrendCommonLogic
{
    /**
     * [makeRowData 生成一组号码以及号码属性, 通过遍历0-9数字的方式]
     * @param  [Int] $iNum          [万千百十个位]
     * @param  [String] $sBall      [某位上的开奖号码值]
     * @param  [Array] $aLostTimes  [号码遗漏次数缓存]
     * @return [Array]              [一条奖期的开奖号码分析属性数组，格式是：]
     *       [
     *         遗漏次数,
     *         当前开奖号数字 (当前位的号码数字),
     *         号温 (1:冷号, 2:温号, 3:热号),
     *         遗漏条 (开奖号码数字是否是最后一次出现该号码数字,是为1,否为0)
     *       ]
     */
    protected function makeRowData(
        $iNum,
        $key1,
        $sBall,
        & $aOmissionBarStatus,
        & $aLostTimes,
        & $aTimes,
        & $aAvgOmission,
        & $aMaxOmission,
        & $aMaxContinous,
        & $aMaxContinousCache
    ) {
        $result = [];
        $iCount = $this->iCount;
        // 11选5的遗漏次数数组排序是万千百十个位[0-10][11-21][22-32][33-43][44-54]
        for ($i = 0; $i < $iCount; $i++) {
            $iNumber = $this->iCount === 10 && $this->iType !== 'pk10' ? $i : $i + 1;
            $index = $iNum * $this->iCount + $i;
            //当前号码为开奖号码数字
            if ((int)$sBall === $iNumber) {
                $aLostTimes[$index] = 0;
                $iOmission = 0;
                ++$aTimes[$index];
                ++$aMaxContinousCache[$index];
                $aOmissionBarStatus[$index] = $key1;
            } else {
                isset($aLostTimes[$index]) ? ++$aLostTimes[$index] : $aLostTimes[$index] = 1;
                $iOmission = 1;
                $aMaxOmission[$index] = max($aLostTimes[$index], $aMaxOmission[$index]);
                $aMaxContinousCache[$index] = 0;
            }
            if ($aLostTimes[$index] === 0) {
                $aAvgOmission[$index]++;
            }
            $aMaxContinous[$index] = max($aMaxContinousCache[$index], $aMaxContinous[$index]);
            $result[] = [$aLostTimes[$index], $sBall, 1, $iOmission];
        }
        return $result;
    }

    /**
     * [countPairPattern 对子]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [遗漏值]
     */
    protected function countPairPattern($aBalls)
    {
        return (int)($aBalls[0] != $aBalls[1]);
    }

    /**
     * [countNumberDistribution 号码分布 格式: [遗漏次数, 当前数字, 重复次数]]
     * @param  [Array] $aBalls          [开奖号码]
     * @param  [Int]   $iBallsLen       [开奖号码位数]
     * @return [Array]                  [号码分布统计数据数组]
     */
    protected function countNumberDistribution($aBalls, $iBallsLen)
    {
        $times = [];
//        $iCount = $this->iType == 1 ? 10 : 12;
//        $iCount = $this->iCount == 10 ? 10 : 12;
        $iCount = $this->iCount;
        $iStart = ($this->iCount === 10 && $this->iType !== 'pk10') ? 0 : 1;
        if ($iStart === 0) {
            $iCount--;
        }

        for ($iStart; $iStart <= $iCount; $iStart++) {
            $num = 0;
            for ($j = 0; $j < $iBallsLen; $j++) {
                if ((int)$aBalls[$j] === $iStart) {
                    ++$num;
                }
            }
            $times[] = [in_array($iStart, $aBalls, false) ? 0 : 1, $iStart, $num];
        }
        return $times;
    }

    /**
     * [countNumberSizePattern 大小形态 [1,0,1]; 1代表大 0代表小]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [大小形态数组]
     */
    protected function countNumberSizePattern($aBalls)
    {
        return array_map(static function ($item) {
            return (int)($item > 4);
        }, $aBalls);
    }

    /**
     * [countNumberOddEvenPattern 单双形态 [1,0,1]; 1单 0双]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [单双形态数组]
     */
    protected function countNumberOddEvenPattern($aBalls)
    {
        return array_map(function ($item) {
            return (int)($item % 2 !== 0);
        }, $aBalls);
    }

    /**
     * [countNumberOddEvenPattern 质合形态 [1,0,1]; 1质数 0 合数]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [质合形态数组]
     */
    protected function countNumberPrimeCompositePattern($aBalls)
    {
        $pArray = [1, 2, 3, 5, 7];
        $result = [];
        foreach ($aBalls as $key => $value) {
            $result[] = (int)in_array($value, $pArray, false);
        }
        return $result;
    }

    /**
     * [countNumber012Pattern 012形态 [1,0,1]; 模3余数]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [质合形态数组]
     */
    protected function countNumber012Pattern($aBalls)
    {
        return array_map(static function ($item) {
            return ($item % 3);
        }, $aBalls);
    }

    /**
     * [countNumberSamePattern 判断号码是否豹子, 组三, 组六]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [遗漏值]
     */
    protected function countNumberSamePattern($aBalls)
    {
        switch (count(array_count_values($aBalls))) {
            case 1: // 是否豹子
                $aNumberStyle = [[0], [1], [1]];
                break;
            case 2: // 是否组三
                $aNumberStyle = [[1], [0], [1]];
                break;
            case 3: // 是否组六
            default:
                $aNumberStyle = [[1], [1], [0]];
                break;
        }
        return $aNumberStyle;
    }

    /**
     * [countNumberRangePattern 跨度]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [遗漏值]
     */
    protected function countNumberRangePattern($aBalls, $iBallsLen)
    {
        return max($aBalls) - min($aBalls);
    }

    /**
     * [countNumberSumPattern 和值]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [遗漏值]
     */
    protected function countNumberSumPattern($aBalls)
    {
        return array_sum($aBalls);
    }

    /**
     * [countNumberSumMantissaPattern 和值尾数]
     * @param  [Int] $iSum [开奖号码和值]
     * @return [Array]         [遗漏值]
     */
    protected function countNumberSumMantissaPattern($iSum)
    {
        return substr(strval($iSum), -1);
    }

    /**
     * [countNumberRangeTrendPattern 跨度走势]
     * @param  [Array] $aBalls [开奖号码分解数组]
     * @return [Array]         [遗漏值, 当前球内容, 重复次数]
     */
    protected function countNumberRangeTrendPattern($aBalls)
    {
        $times = [];
        $kd = abs($aBalls[1] - $aBalls[0]);
        $i = $this->iType === 'pk10' ? 1 : 0;
        for ($i; $i <= 10; $i++) {
            $times[] = [($i == $kd) ? 0 : 1, $i];
        }
        return $times;
    }

    /**
     * [countPairAndRangeOmission 对子, 跨度走势遗漏]
     * @param  [Array]   $data       [待分析的数据]
     * @param  [Integer] $i          [数据数组索引值]
     * @param  [Array]   $tempOmissionForPair  [对子走势遗漏值]
     * @param  [Array]   $tempOmissionForRange [跨度走势遗漏值]
     * @return [Array]               [分析后的数据]
     */
    protected function countPairAndRangeOmission(
        & $data,
        $i,
        & $tempOmissionForPair,
        & $tempOmissionForRange,
        & $aTimes,
        & $aAvgOmission,
        & $aMaxOmission,
        & $aMaxContinous,
        & $aMaxContinousCache
    ) {
        $iPairColumnIndex = 20;
        $iRangeColumnIndex = 31;
        // 对子走势遗漏
        $data[$i][4] ? ++$tempOmissionForPair : $tempOmissionForPair = 0;
        $data[$i][4] = $tempOmissionForPair;
        // ---------对子的4项统计
        if (!$data[$i][4]) {
            ++$aTimes[$iPairColumnIndex];
            ++$aMaxContinousCache[$iPairColumnIndex];
            $aMaxContinous[$iPairColumnIndex] = max($aMaxContinous[$iPairColumnIndex],
                $aMaxContinousCache[$iPairColumnIndex]);
        } else {
            $aMaxContinousCache[$iPairColumnIndex] = 0;
        }
        // $aAvgOmission[$iPairColumnIndex] += $tempOmissionForPair;
        if ($tempOmissionForPair == 0) {
            $aAvgOmission[$iPairColumnIndex]++;
        }
        $aMaxOmission[$iPairColumnIndex] = max($aMaxOmission[$iPairColumnIndex], $tempOmissionForPair);
        // 跨度走势遗漏
        for ($n = 0; $n < 10; $n++) {
            $m = $iRangeColumnIndex + $n;
            $data[$i][6][$n][0] ? ++$tempOmissionForRange[$n] : $tempOmissionForRange[$n] = 0;
            $data[$i][6][$n][0] = $tempOmissionForRange[$n];
            // 跨度的4项统计
            if (!$data[$i][6][$n][0]) {
                ++$aTimes[$m];
                ++$aMaxContinousCache[$m];
                $aMaxContinous[$m] = max($aMaxContinous[$m], $aMaxContinousCache[$m]);
            } else {
                $aMaxContinousCache[$m] = 0;
            }
            // $aAvgOmission[$m] += $tempOmissionForRange[$n];
            if ($tempOmissionForRange[$n] == 0) {
                $aAvgOmission[$m]++;
            }
            $aMaxOmission[$m] = max($aMaxOmission[$m], $tempOmissionForRange[$n]);
        }
    }

    /**
     * [countNumberStyleOmission 计算 豹子 组三 组六 的遗漏值]
     * @param  [Array]   $data     [统计数据]
     * @param  [Integer] $i        [数据记录的循环索引]
     * @param  [Int]     $tempOmissionForNumberStyle     [豹子 组三 组六的遗漏次数统计缓存]
     * @return [Array]   $data     [分析后的统计数据]
     */
    protected function countNumberStyleOmission(
        & $data,
        $i,
        & $tempOmissionForNumberStyle,
        & $aTimes,
        & $aAvgOmission,
        & $aMaxOmission,
        & $aMaxContinous,
        & $aMaxContinousCache
    ) {
        $iCellNum = $this->iCellNum;
        for ($j = 10; $j < 13; $j++) {
            $n = $j - 10;
            $data[$i][$j][0] ? ++$tempOmissionForNumberStyle[$n] : $tempOmissionForNumberStyle[$n] = 0;
            $data[$i][$j][0] = $tempOmissionForNumberStyle[$n];
            // 豹子 组三 组六的4项统计
            $m = $iCellNum + $n;
            if (!$data[$i][$j][0]) {
                ++$aTimes[$m];
                ++$aMaxContinousCache[$m];
                $aMaxContinous[$m] = max($aMaxContinous[$m], $aMaxContinousCache[$m]);
            } else {
                $aMaxContinousCache[$m] = 0;
            }
            // $aAvgOmission[$m] += $tempOmissionForNumberStyle[$n];
            if ($tempOmissionForNumberStyle[$n] == 0) {
                $aAvgOmission[$m]++;
            }
            $aMaxOmission[$m] = max($aMaxOmission[$m], $tempOmissionForNumberStyle[$n]);
        }
    }

    /**
     * [countDistributionOmission 号码分布的遗漏次数]
     * @param  [Array]   $data     [统计数据]
     * @param  [Integer] $i        [数据记录的循环索引]
     * @param  [Int]     $tempOmissionForDistribution     [号码分布的遗漏次数统计缓存]
     * @return [Array]   $data     [分析后的统计数据]
     */
    protected function countDistributionOmission(
        & $data,
        $i,
        & $tempOmissionForDistribution,
        & $aTimes,
        & $aAvgOmission,
        & $aMaxOmission,
        & $aMaxContinous,
        & $aMaxContinousCache
    ) {
        $iIndex = $this->iIndex;
        $iCount = $this->iCount;
        $iDistributionStart = $this->iBallNum * 10 + (int)($this->iBallNum == 2);
        for ($n = 0; $n < $iCount; $n++) {
            !$data[$i][$iIndex][$n][2] ? ++$tempOmissionForDistribution[$n] : $tempOmissionForDistribution[$n] = 0;
            $data[$i][$iIndex][$n][0] = $tempOmissionForDistribution[$n];
            // 号码分布的4项统计
            $m = $iDistributionStart + $n;
            if (!$data[$i][$iIndex][$n][0]) {
                $aTimes[$m] += $data[$i][$iIndex][$n][2];
                ++$aMaxContinousCache[$m];
                $aMaxContinous[$m] = max($aMaxContinous[$m], $aMaxContinousCache[$m]);
            } else {
                $aMaxContinousCache[$m] = 0;
            }
            // $aAvgOmission[$m] += $tempOmissionForDistribution[$n];
            if ($tempOmissionForDistribution[$n] == 0) {
                $aAvgOmission[$m]++;
            }
            $aMaxOmission[$m] = max($aMaxOmission[$m], $tempOmissionForDistribution[$n]);
        }
    }

    public function countNumberTrend($fArr, $eArr)
    {
        $result = [];
        foreach ($eArr as $i => $v) {
            if (isset($fArr[$i])) {
                $result[$i] = ($v == $fArr[$i]) ? 2 : ($v > $fArr[$i] ? 1 : 3);
            } else {
                $result[$i] = 0;
            }
        }
        return $result;
    }
}