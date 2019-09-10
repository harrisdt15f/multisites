<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 9/10/2019
 * Time: 9:49 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;

trait LotteryTrendLogic
{
    public static $iIssueLimit = 100; // 最多取x条奖期数据供走势分析
    // 不同统计类型统计号码分布数据时, 在data数组中的起始下标
    protected static $aIndexs = [
        '5' => 7,
        '4' => 6,
        '3' => 5,
        '3f' => 5,
        '3m' => 5,
        '3e' => 5,
        '2f' => 5,
        '2e' => 5,
        '7a' => 7,
        '2gy' => 4,
        '5f' => 7,
        '5e' => 7
    ];
    // 不同统计类型实际需要遍历的标准列数(球个数 + 分布 + [二星的跨度]) * 10 + [1 (二星的对子)]
    protected static $aCellNums = [
        '5' => 60,
        '4' => 50,
        '3' => 40,
        '3f' => 40,
        '3m' => 40,
        '3e' => 40,
        '2f' => 41,
        '2e' => 41,
        '2gy' => 30,
        '5f' => 50,
        '5e' => 50
    ];
    // 不同统计类型号码截取的起始下标
    protected static $aPositions = [
        '5' => 0,
        '4' => 1,
        '3' => 0,
        '3f' => 0,
        '3m' => 1,
        '3e' => 2,
        '2f' => 0,
        '2e' => 3,
        '2gy' => 0,
        '5f' => 0,
        '5e' => 5
    ];

    protected static $aClips = [
        'ssc' => '',
        'lotto' => ' ',
        'sd' => '',
        'p3p5' => '',
        'k3' => '',
        'pk10' => ','
    ];

    protected $lotteryId = ''; // 彩种ID 标识
    protected $iDataLen = 0;
    protected $iIndex = 0;
    protected $iBallNum = 0;
    protected $iCellNum = 0; // 标准列数(球个数 + 分布 + [二星的跨度]) * 10 + [1 (二星的对子)]
    protected $iType = 'ssc'; // 彩系, 1: SSC, 2: 11-5
    protected $iCount = 10; //合法数字个数

    /**
     * [getIssuesByParams 根据查询参数获取奖期开奖号码]
     * @param  [integer] $sLotteryId [彩种id]
     * @param  [integer] $iNumType   [位数]
     * @param  [integer] $iBeginTime [起始时间秒数]
     * @param  [integer] $iEndTime   [结束时间秒数]
     * @param  [integer] $iCount     [记录条数]
     * @return [Array]               [返回分析数据]
     */
    public function getIssuesByParams(
        $sLotteryId,
        $iNumType = 5,
        $iBeginTime = null,
        $iEndTime = null,
        $iCount = null,
        $colums = []
    ) {
        if (!$sLotteryId || !$iNumType || (!$iCount && !$iBeginTime && !$iEndTime)) {
            return false;
        }
        if (empty($colums)) {
            $aColumns = ['issue', 'official_code', 'end_time'];
        } else {
            $aColumns = $colums;
        }
        $aCondtions = [
            ['lottery_id', '=', $sLotteryId],
            ['official_code', '!=', null],
        ];
        $oQuery = self::where($aCondtions);
        // TIP 如果起止时间和奖期数都有值，优先使用起止时间条件
        if ($iBeginTime || $iEndTime) {
            if ($iBeginTime && $iEndTime) {
                $oQuery->whereBetween('end_time', [$iBeginTime, $iEndTime]);
            } else {
                $sOperator = $iBeginTime ? '>=' : '<=';
                $iTime = $iBeginTime ?: $iEndTime;
                $oQuery->where('end_time', $sOperator, $iTime);
            }
            $iCount = static::$iIssueLimit;
        }
        $oQuery = $oQuery->orderBy('end_time', 'desc');
        $oQuery = $oQuery->take($iCount);
        $data = $oQuery->get($aColumns)->sortBy('end_time')->makeHidden(['end_time'])->toArray();
        return $data;
    }

    /**
     * [getProbabilityOfOccurrenceByParams 根据查询参数获取奖期开奖号码, 并生成分析后的冷热数据]
     * @param  [integer] $sLotteryId [彩种id]
     * @param  [integer] $iNumType   [位数]
     * @param  [integer] $iBeginTime [起始时间秒数]
     * @param  [integer] $iEndTime   [结束时间秒数]
     * @param  [integer] $iCount     [记录条数]
     * @return [Array]               [返回分析数据]
     */
    public function getProbabilityOfOccurrenceByParams(
        $sLotteryId,
        $iNumType = 5,
        $iBeginTime = null,
        $iEndTime = null,
        $iCount = null
    ): array {
        $data = $this->getIssuesByParams($sLotteryId, $iNumType, $iBeginTime, $iEndTime, $iCount);
        if (!$data) {
            $result = [
                'isSuccess' => 0,
                'type' => 'error',
                'msg' => 'No Data',
                'errno' => '',
            ];
            return $result;
        }
        $oLottery = LotteryList::where('en_name', $sLotteryId);
        $strSeriesId = $oLottery->series_id;
        $this->iCount = count(explode(',', $oLottery->valid_code));
        $this->iType = $strSeriesId === 'p3p5' ? 'sd' : $strSeriesId;
        $aOccurrenceData = $this->generateOccurrenceData($data);
        $result = [
            'isSuccess' => 1,
            'data' => $aOccurrenceData,
        ];
        return $result;
    }

    /**
     * [getTrendDataByParams 根据查询参数获取奖期开奖号码, 并生成分析后的走势数据]
     * @param  [integer] $sLotteryId [彩种id]
     * @param  [integer] $iNumType   [位数]
     * @param  [integer] $iBeginTime [起始时间秒数]
     * @param  [integer] $iEndTime   [结束时间秒数]
     * @param  [integer] $iCount     [记录条数]
     * @return [Array]               [返回分析数据]
     */
    public function getTrendDataByParams(
        $sLotteryId,
        $iNumType = 5,
        $iBeginTime = null,
        $iEndTime = null,
        $iCount = null
    ): array {
        $oLottery = LotteryList::where('en_name', $sLotteryId)->first();
        $strSeriesId = $oLottery->series_id;
        $this->lotteryId = $sLotteryId;
        $this->iCount = count(explode(',', $oLottery->valid_code));
        // 获取series_id
        $this->iType = $strSeriesId === 'p3p5' ? 'sd' : $strSeriesId;
        // 获取奖期开奖数据
        if ($this->iType === 'lhc') {
            $data = $this->getIssuesByParams($sLotteryId, $iNumType, $iBeginTime, $iEndTime, $iCount,
                ['issue', 'official_code', 'lottery_id']);
        } else {
            $data = $this->getIssuesByParams($sLotteryId, $iNumType, $iBeginTime, $iEndTime, $iCount);
        }
        // 没有奖期直接额返回错误
        if (!$data) {
            return $data;
        }
        $statistics = $hotAndCold = $aOmissionBarStatus = [];
        $this->iDataLen = count($data);
        $this->iIndex = static::$aIndexs[$iNumType];
        $this->iCellNum = $this->iType === 'ssc' ? static::$aCellNums[$iNumType] : 66;
        $this->iBallNum = (int)substr($iNumType, 0, 1);
        if ($this->iType === 'lhc') {
            $this->generateLhcTrendData($data, $statistics, $aOmissionBarStatus, $iNumType);
        } elseif ($this->iType === 'pk10') {
            $this->generatePK10TrendData($data, $statistics, $aOmissionBarStatus, $iNumType);
        } elseif ($this->iType === 'k3') {
            $this->generateK3TrendData($data, $statistics, $hotAndCold, $aOmissionBarStatus, $iNumType);
        } else {
            $this->generateTrendData($data, $statistics, $hotAndCold, $aOmissionBarStatus, $iNumType);
        }

        // TODO 目前的BaseController中的halt函数只能输出一个data属性，这里的statistics同级属性无法追加, 先不用halt来组织输出
        return [
            'data' => $data,
            'statistics' => $statistics,
            'omissionBarStatus' => $aOmissionBarStatus,
        ];
    }

    /**
     * [generateOccurrenceData 生成号码冷热统计的数据]
     * @param  &      $data  [奖期数据的引用]
     * @return [type]       [description]
     */
    public function generateOccurrenceData(& $data)
    {
        $sClip = static::$aClips[$this->iType];
//        $iCount = $this->iType == 1 ? 10 : 11;
        $iCount = $this->iCount;
        $aOccurrenceData = [];
        foreach ($data as $key1 => $oIssue) {
            $sBalls = $oIssue['official_code'];
            $aBalls = $sClip ? explode($sClip, $sBalls) : str_split($sBalls);
            $iBallsLen = count($aBalls);
            foreach ($aBalls as $key2 => $value) {
                for ($i = 0; $i < $iCount; $i++) {
                    $index = $key2 * 10 + $i;
                    $iNumber = $this->iType === 'ssc' ? $i : $i + 1;
                    if (!isset($aOccurrenceData[$key2])) {
                        $aOccurrenceData[$key2] = [];
                    }
                    //当前号码为开奖号码数字
                    if ($value === $iNumber) {
                        isset($aOccurrenceData[$key2][$iNumber]) ? ++$aOccurrenceData[$key2][$iNumber] : $aOccurrenceData[$key2][$iNumber] = 0;
                    }
                }
            }
        }
        $result = [];
        // 降序排列
        foreach ($aOccurrenceData as $key => $value) {
            arsort($value);
            $iNumSum = array_sum($value);
            $value['sum'] = $iNumSum;
            $result[] = $value;
        }
        return $result;
    }

    /**
     * 生成PK10的走势数据
     * @param $data
     * @param $statistics
     * @param $aOmissionBarStatus
     * @param $iNumType
     */
    public function generatePK10TrendData(& $data, & $statistics, &$aOmissionBarStatus, $iNumType)
    {
        $sClip = ',';
        $aLostTimes = [];
        $iCount = $this->iCount;
        switch ($this->iBallNum) {
            case '2':
                // 二星对子遗漏和跨度遗漏
                $tempOmissionForPair = 0;
                $tempOmissionForRange = array_fill(0, $iCount, 0);
                break;
        }
        $tempOmissionForDistribution = array_fill(0, $iCount, 0);
        // -------------------start 统计数据--------------------
        $iCellNum = $this->iCellNum;
        $iAdditional = $this->iBallNum; // 3星额外有豹子 组三 组六的统计列
        $iColumnNum = $iCellNum + $iAdditional;
        $aTimes = array_fill(0, $iColumnNum, 0);
        $aAvgOmission = array_fill(0, $iColumnNum, 0);
        $aMaxOmission = array_fill(0, $iColumnNum, 0);
        $aMaxContinous = $aMaxContinousCache = array_fill(0, $iColumnNum, 0);
        $aOmissionBarStatus = array_fill(0, $this->iBallNum * $iCount, -1);

        $lastWinNumber = '';
        foreach ($data as $key1 => $oIssue) {
            $data[$key1][0] = $oIssue['issue'];
            $data[$key1][1] = $oIssue['official_code'];
            unset($data[$key1]['issue'], $data[$key1]['official_code']);
            $sBalls = $data[$key1][1];
            // 如果是时时彩, 先截取号码位数, 5星/4星/前3/后3/前2/后2
            $sBalls = explode($sClip, $sBalls);
            $iPos = static::$aPositions[$iNumType];
            $aBalls = array_slice($sBalls, $iPos, $this->iBallNum);
            $iBallsLen = count($aBalls);
            foreach ($aBalls as $key2 => $value) {
                $value = (int)$value;
                $arr = $this->makeRowData($key2, $key1, $value, $aOmissionBarStatus, $aLostTimes, $aTimes,
                    $aAvgOmission, $aMaxOmission, $aMaxContinous, $aMaxContinousCache);
                $data[$key1][$key2 + 2] = $arr;
            }

            // 升平降所参考的上一期开奖号码
            if (empty($lastWinNumber)) {
                $lastIssue = LotteryIssue::where('lottery_id', $this->lotteryId)->where('issue', '<',
                    $data[$key1][0])->first(['issue', 'official_code']);
                if ($lastIssue) {
                    if ($lastIssue->wn_numbers) {
                        $lastWinNumber = $lastIssue->official_code;
                        $lastWinNumber = explode($sClip, $lastWinNumber);
                        $lastWinNumber = array_slice($lastWinNumber, $iPos, $this->iBallNum);
                    } else {
                        $lastWinNumber = array_fill(0, $this->iBallNum, 0);
                    }

                } else {
                    $lastWinNumber = array_fill(0, $this->iBallNum, 0);
                }
            }

            // 号码分布
            $data[$key1][] = $this->countNumberDistribution($aBalls, $iBallsLen);
            switch ($this->iBallNum) {
                case '2'://[期号, winball, 第一位,第二位,号码分布,跨度走势,冠亚和大小,冠亚和单双,冠亚和升平降,和值]
                    // 跨度
                    $data[$key1][] = $this->countNumberRangeTrendPattern($aBalls);

                    // 大小
                    $data[$key1][] = array_map(function ($item) {
                        return (int)($item > 5);
                    }, $aBalls);

                    // 单双
                    $data[$key1][] = array_map(function ($item) {
                        return ($item % 2) ? 3 : 2;
                    }, $aBalls);

                    // 升平降
                    $_upAndDown = $this->countNumberTrend($lastWinNumber, $aBalls);
                    $data[$key1][] = $_upAndDown;

                    $this->countPairAndRangeOmissionPk10($data, $key1, $tempOmissionForPair, $tempOmissionForRange,
                        $aTimes, $aAvgOmission, $aMaxOmission, $aMaxContinous, $aMaxContinousCache);
                    $this->countDistributionOmission($data, $key1, $tempOmissionForDistribution, $aTimes, $aAvgOmission,
                        $aMaxOmission, $aMaxContinous, $aMaxContinousCache);
                    break;
                case '5'://[期号, winball, 第一位,第二位,第3位,第4位,第5位,号码分布,大小,单双,升平降]
                    $data[$key1][] = array_map(function ($item) {
                        return (int)($item > 5);
                    }, $aBalls);//大小
                    $data[$key1][] = array_map(function ($item) {
                        return ($item % 2) ? 3 : 2;
                    }, $aBalls);
                    $data[$key1][] = $this->countNumberTrend($lastWinNumber, $aBalls);
                    $this->countDistributionOmission($data, $key1, $tempOmissionForDistribution, $aTimes, $aAvgOmission,
                        $aMaxOmission, $aMaxContinous, $aMaxContinousCache);
                    break;
            }
            $lastWinNumber = $aBalls;
        }
        // pr($aTimes);exit;
        // 平均遗漏值
        $iLenTimes = count($aAvgOmission);
        for ($i = 0; $i < $iLenTimes; $i++) {
            // $aAvgOmission[$i] = round($aAvgOmission[$i] / $this->iDataLen);
            if ($aAvgOmission[$i] == 0) {
                $aAvgOmission[$i] = $this->iDataLen + 1;
            } else {
                $aAvgOmission[$i] = floor($this->iDataLen / $aAvgOmission[$i]);
            }
        }

        //   pr($aMaxContinous);exit;
        $statistics = [$aTimes, $aAvgOmission, $aMaxOmission, $aMaxContinous];

    }

    public function countPairAndRangeOmissionPk10(
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
        $iRangeColumnIndex = 31;
        // 跨度走势遗漏
        for ($n = 0; $n < 10; $n++) {
            $m = $iRangeColumnIndex + $n;
            $data[$i][5][$n][0] ? ++$tempOmissionForRange[$n] : $tempOmissionForRange[$n] = 0;
            $data[$i][5][$n][0] = $tempOmissionForRange[$n];
            // 跨度的4项统计
            if (!$data[$i][5][$n][0]) {
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
     * [generateTrendData 生成走势数据]
     * @param  &      $data  [奖期数据的引用]
     * @param  &      $statistics  [统计数据的引用]
     * @param  &      $hotAndCold  [号温数据的引用]
     * @param  &      $aOmissionBarStatus  [遗漏条数据的引用]
     * @param  [Integer] $iNumType        [号码类型]
     * @return [type]                     [description]
     */
    public function generateTrendData(& $data, & $statistics, & $hotAndCold, & $aOmissionBarStatus, $iNumType)
    {
        $sClip = static::$aClips[$this->iType];
        $aLostTimes = [];
        $iCount = $this->iCount;
        // 根据中奖号码个数初始化待填充的数组
        switch ($this->iBallNum) {
            case '2':
                // 二星对子遗漏和跨度遗漏
                $tempOmissionForPair = 0;
                $tempOmissionForRange = array_fill(0, $iCount, 0);
                break;
            case '3':
                // 豹子, 组三, 组六的号码分布遗漏
                $tempOmissionForNumberStyle = array_fill(0, $iCount, 0);
                break;
            // case '4':
            // case '5':
            // default:
            //     // 号码分布遗漏, 所有类型都有
            //     $tempOmissionForDistribution = array_fill(0, 10, 0);
            //     break;
        }
        $tempOmissionForDistribution = array_fill(0, $iCount, 0);
        // -------------------start 统计数据--------------------
        $iCellNum = $this->iCellNum;
        $iAdditional = $this->iBallNum === 3 ? 3 : 0; // 3星额外有豹子 组三 组六的统计列
        $iColumnNum = $iCellNum + $iAdditional;
        $aTimes = array_fill(0, $iColumnNum, 0);
        $aAvgOmission = array_fill(0, $iColumnNum, 0);
        $aMaxOmission = array_fill(0, $iColumnNum, 0);
        $aMaxContinous = $aMaxContinousCache = array_fill(0, $iColumnNum, 0);
        $aOmissionBarStatus = array_fill(0, $this->iBallNum * $iCount, -1);
        // -------------------end 统计数据----------------------
        // 遍历统计数据, 需要依据页面展现顺序，依次填充数据
        // for ($key1 = $this->iDataLen - 1; $key1 >= 0; $key1--) {
        //     $oIssue = $data[$key1];
        foreach ($data as $key1 => $oIssue) {
            $data[$key1][0] = $oIssue['issue'];
            $data[$key1][1] = $oIssue['official_code'];
            unset($data[$key1]['issue'], $data[$key1]['official_code']);
            $sBalls = $data[$key1][1];
            // 如果是时时彩, 先截取号码位数, 5星/4星/前3/后3/前2/后2
            if (!$sClip) {
                $iPos = static::$aPositions[$iNumType];
                $sBalls = substr($sBalls, $iPos, $this->iBallNum);
                // $data[$key1][1] = $sBalls; // TIP 调整为前台处理号码
            }
            // 如果是时时彩, 则按号码位数分割, 11选5则按空格分割
            $aBalls = $sClip ? explode($sClip, $sBalls) : str_split($sBalls);
            $iBallsLen = count($aBalls);
            // 遍历每一位号码，生成每一位号码在0-9数字上的分布数据
            foreach ($aBalls as $key2 => $value) {
                $value = (int)$value;
                $arr = $this->makeRowData(
                    $key2,
                    $key1,
                    $value,
                    $aOmissionBarStatus,
                    $aLostTimes,
                    $aTimes,
                    $aAvgOmission,
                    $aMaxOmission,
                    $aMaxContinous,
                    $aMaxContinousCache
                );
                $data[$key1][$key2 + 2] = $arr;
                // $aAllNumbers[$key2 + 2] = $value;
            }
            switch ($this->iBallNum) {
                case '2':
                    $data[$key1][$this->iIndex - 1] = $this->countPairPattern($aBalls);
                    $data[$key1][] = $this->countNumberDistribution($aBalls, $iBallsLen);
                    $data[$key1][] = $this->countNumberRangeTrendPattern($aBalls);
                    $data[$key1][] = $this->countNumberSumPattern($aBalls);
                    $this->countPairAndRangeOmission(
                        $data,
                        $key1,
                        $tempOmissionForPair,
                        $tempOmissionForRange,
                        $aTimes,
                        $aAvgOmission,
                        $aMaxOmission,
                        $aMaxContinous,
                        $aMaxContinousCache
                    );
                    $this->countDistributionOmission(
                        $data,
                        $key1,
                        $tempOmissionForDistribution,
                        $aTimes,
                        $aAvgOmission,
                        $aMaxOmission,
                        $aMaxContinous,
                        $aMaxContinousCache
                    );
                    break;
                case '3':
                    $data[$key1][] = $this->countNumberDistribution($aBalls, $iBallsLen);

                    $data[$key1][] = $this->countNumberSizePattern($aBalls);
                    $data[$key1][] = $this->countNumberOddEvenPattern($aBalls);
                    $data[$key1][] = $this->countNumberPrimeCompositePattern($aBalls);
                    $data[$key1][] = $this->countNumber012Pattern($aBalls);
                    $aNumberStyle = $this->countNumberSamePattern($aBalls);
                    $data[$key1][] = $aNumberStyle[0];
                    $data[$key1][] = $aNumberStyle[1];
                    $data[$key1][] = $aNumberStyle[2];
                    $data[$key1][] = $this->countNumberRangePattern($aBalls, $iBallsLen);
                    $iSum = $this->countNumberSumPattern($aBalls);
                    $iSumTail = $this->countNumberSumMantissaPattern($iSum);
                    $data[$key1][] = $iSum;
                    $data[$key1][] = $iSumTail;

                    $this->countDistributionOmission(
                        $data,
                        $key1,
                        $tempOmissionForDistribution,
                        $aTimes,
                        $aAvgOmission,
                        $aMaxOmission,
                        $aMaxContinous,
                        $aMaxContinousCache
                    );
                    $this->countNumberStyleOmission(
                        $data,
                        $key1,
                        $tempOmissionForNumberStyle,
                        $aTimes,
                        $aAvgOmission,
                        $aMaxOmission,
                        $aMaxContinous,
                        $aMaxContinousCache
                    );
                    break;
                case '5':
                case '4':
                default:
                    $data[$key1][] = $this->countNumberDistribution($aBalls, $iBallsLen);
                    $this->countDistributionOmission(
                        $data,
                        $key1,
                        $tempOmissionForDistribution,
                        $aTimes,
                        $aAvgOmission,
                        $aMaxOmission,
                        $aMaxContinous,
                        $aMaxContinousCache
                    );
                    break;
            }
        }
        // 平均遗漏值
        $iLenTimes = count($aAvgOmission);
        for ($i = 0; $i < $iLenTimes; $i++) {
            // $aAvgOmission[$i] = round($aAvgOmission[$i] / $this->iDataLen);
            if ($aAvgOmission[$i] == 0) {
                $aAvgOmission[$i] = $this->iDataLen + 1;
            } else {
                $aAvgOmission[$i] = floor($this->iDataLen / $aAvgOmission[$i]);
            }
        }
        $statistics = [$aTimes, $aAvgOmission, $aMaxOmission, $aMaxContinous];
        // $aNumberTemp = array_slice($aTimes, $this->iBallNum * 10 + intval($this->iBallNum == 2), 10);
        // $hotAndCold = $aNumberTemp; // $this->generateHotAndColdNumber($aNumberTemp);
        // pr($aNumberTemp);
        // pr(json_encode($data));exit;
    }


    /**
     * [generateTrendData 生成走势数据]
     * @param  &      $data  [奖期数据的引用]
     * @param  &      $statistics  [统计数据的引用]
     * @param  &      $hotAndCold  [号温数据的引用]
     * @param  &      $aOmissionBarStatus  [遗漏条数据的引用]
     * @param  [Integer] $iNumType        [号码类型]
     * @return [type]                     [description]
     */
    public function generateK3TrendData(& $data, & $statistics, & $hotAndCold, & $aOmissionBarStatus, $iNumType)
    {
        // $aClips = ['', ' '];
        $sClip = static::$aClips[$this->iType];
        $aLostTimes = [];
        // $iCount = $this->iType == 1 ? 10 : 11;
        $iCount = $this->iCount;
        // $aAllNumbers = [];
        // 根据中奖号码个数初始化待填充的数组
        switch ($this->iBallNum) {
            case '3':
                // 豹子, 组三, 组六的号码分布遗漏
                $tempOmissionForNumberStyle = array_fill(0, $iCount, 0);
                break;
        }
        $tempOmissionForDistribution = array_fill(0, $iCount, 0);
        // -------------------start 统计数据--------------------

        $iCellNum = $this->iCellNum;
        $iAdditional = $this->iBallNum == 3 ? 3 : 0; // 3星额外有豹子 组三 组六的统计列
        $iColumnNum = $iCellNum + $iAdditional;
        $aTimes = array_fill(0, 29, 0);
        $aAvgOmission = array_fill(0, 29, 0);
        $aMaxOmission = array_fill(0, 29, 0);
        $aMaxContinous = $aMaxContinousCache = array_fill(0, 29, 0);
        $aOmissionBarStatus = array_fill(0, $this->iBallNum * $iCount, -1);
        // -------------------end 统计数据----------------------
        // 遍历统计数据, 需要依据页面展现顺序，依次填充数据
        // for ($key1 = $this->iDataLen - 1; $key1 >= 0; $key1--) {
        //     $oIssue = $data[$key1];

        foreach ($data as $key1 => $oIssue) {
            $data[$key1][0] = $oIssue['issue'];
            $data[$key1][1] = $oIssue['official_code'];
            unset($data[$key1]['issue'], $data[$key1]['official_code']);

            $sBalls = $data[$key1][1];
            // 如果是时时彩, 先截取号码位数, 5星/4星/前3/后3/前2/后2
            if (!$sClip) {
                $iPos = static::$aPositions[$iNumType];
                $sBalls = substr($sBalls, $iPos, $this->iBallNum);
                // $data[$key1][1] = $sBalls; // TIP 调整为前台处理号码
            }

            // 如果是时时彩, 则按号码位数分割, 11选5则按空格分割
            $aBalls = $sClip ? explode($sClip, $sBalls) : str_split($sBalls);
            $iBallsLen = count($aBalls);
            // pr($aBalls);exit;

            // 遍历每一位号码，生成每一位号码在0-9数字上的分布数据
            foreach ($aBalls as $key2 => $value) {
                $value = (int)$value;
                $arr = $this->makeK3RowData($key2, $key1, $value, $aOmissionBarStatus, $aLostTimes, $aTimes,
                    $aAvgOmission, $aMaxOmission, $aMaxContinous, $aMaxContinousCache);
                $data[$key1][$key2 + 2] = $arr;
                // $aAllNumbers[$key2 + 2] = $value;
            }

            $data[$key1][] = $this->countNumberDistribution($aBalls, $iBallsLen);

            $this->generateK3Ddata($aBalls, $data, $key1);

        }


        $iLenTimes = count($aAvgOmission);
        for ($i = 0; $i < $iLenTimes; $i++) {
            // $aAvgOmission[$i] = round($aAvgOmission[$i] / $this->iDataLen);
            if ($aAvgOmission[$i] == 0) {
                $aAvgOmission[$i] = $this->iDataLen + 1;
            } else {
                $aAvgOmission[$i] = floor($this->iDataLen / $aAvgOmission[$i]);
            }
        }

        $aTimes[24] = $aAvgOmission[24] = $aMaxOmission[24] = $aMaxContinous[24] = 0;


        //号码分布
        $data1 = [];
        foreach ($data as $key => $info) {
            foreach ($info as $key1 => $info1) {
                if (in_array($key1, [5])) {
                    foreach ($info1 as $key2 => $val2) {
                        $data1[$key2][] = $val2[0];
                    }
                }
            }
        }

        foreach ($data as $key => $info) {
            foreach ($info as $key1 => $info1) {
                if (in_array($key1, [7, 8, 9, 10])) {
                    $data1[$key1][] = $info1[0];
                }
            }
        }

        foreach ($data1 as $key1 => $val1) {

            $curMaxContinous = $curAvgOmission = 0;
            $countMaxContinous = $countAvgOmission = 0;

            $arrayCount = array_count_values($val1);
            $aTimes[$key1 + 18] = isset($arrayCount[0]) ? $arrayCount[0] : 0;

            $aMaxOmission[$key1 + 18] = max($val1);

            foreach ($val1 as $key2 => $val2) {
                //最大连出值
                if ($val2 == 0) {
                    $curMaxContinous++;
                } else {
                    if ($curMaxContinous > $countMaxContinous) {
                        $countMaxContinous = $curMaxContinous;
                    }
                    $curMaxContinous = 0;
                }
            }
            $count = isset($arrayCount[0]) ? $arrayCount[0] : 0;
            $count = ($this->iDataLen - $count);
            if ($count <= 0) {
                $count = 1;
            }
            $aAvgOmission[$key1 + 18] = floor($this->iDataLen / $count);

            $aMaxContinous[$key1 + 18] = $countMaxContinous;
        }


        $statistics = [$aTimes, $aAvgOmission, $aMaxOmission, $aMaxContinous];

    }


    /**
     * [generateHotAndColdNumber 生成号温的判断规则, 热号, 冷号号码数组, 因为有可能几个号码值的出现次数是一样的, 如都是一次，那么就都是冷号]
     * @param  [Array] $aNumberTemp [所有开奖号码数组]
     * @return [Array]              [热号, 冷号]
     */
    private function generateHotAndColdNumber(& $aNumberTemp)
    {
        // $aNumberTemp = array_count_values($aAllNumbers);
        arsort($aNumberTemp);
        $aKeys = array_keys($aNumberTemp);
        $aValues = array_values($aNumberTemp);
        $iTop = ($aValues[0]);
        $iBottom = ($aValues[count($aValues) - 1]);
        $aHottestNums = [];
        $aColdestNums = [];
        $aWarmNums = [];
        foreach ($aNumberTemp as $key => $value) {
            if ($value == $iTop) {
                $aHottestNums[] = $key;
            } else {
                if ($value == $iBottom) {
                    $aColdestNums[] = $key;
                } else {
                    $aWarmNums[] = $key;
                }
            }
        }
        return ['hot' => $aHottestNums, 'cold' => $aColdestNums];
    }

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
    private function makeK3RowData(
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
        //$iCount = $this->iType == 1 ? 10 : 11;
        $iCount = $this->iCount;
        // 11选5的遗漏次数数组排序是万千百十个位[0-10][11-21][22-32][33-43][44-54]
        $iAdditional = $this->iType === 'ssc' ? 0 : $iNum;
        for ($i = 0; $i < $iCount; $i++) {
//            $iNumber = $this->iType == 1 ? $i : $i + 1;
            $iNumber = $this->iCount === 10 ? $i : $i + 1;
            $index = $iNum * 6 + $i;

            //当前号码为开奖号码数字
            if ($sBall === $iNumber) {
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

                // $aOmissionBarStatus[$index] = -1;
            }
            // $aAvgOmission[$index]  += $aLostTimes[$index];
            if ($aLostTimes[$index] == 0) {
                $aAvgOmission[$index]++;
            }
            $aMaxContinous[$index] = max($aMaxContinousCache[$index], $aMaxContinous[$index]);

            $result[] = [$aLostTimes[$index], $sBall, 1, $iOmission];
        }
        return $result;
    }


    private function generateK3Ddata($aBalls, & $data, $key1)
    {

        //$data[$issueIndex -1] = [array_sum($aBalls)];//和值

        //和值单算
        $data[$key1][] = [array_sum($aBalls)];//和值

        $preData = $key1 ? $data[$key1 - 1] : [];

        //三同号
        if (min($aBalls) === max($aBalls)) {
            $threeSame = 0;
        } else {
            $threeSame = empty($preData) ? 1 : $preData[count($data[$key1])][0] + 1;
        }
        $data[$key1][] = [$threeSame];


        //二同号
        if (count(array_unique($aBalls)) < count($aBalls)) {
            $twoNoSame = 0;
        } else {
            $twoNoSame = empty($preData) ? 1 : $preData[count($data[$key1])][0] + 1;
        }
        $data[$key1][] = [$twoNoSame];

        //三不同
        if (count(array_unique($aBalls)) === count($aBalls)) {
            $threeNoSame = 0;
        } else {
            $threeNoSame = empty($preData) ? 1 : $preData[count($data[$key1])][0] + 1;
        }
        $data[$key1][] = [$threeNoSame];

        //三连号
        if (count(array_unique($aBalls)) === count($aBalls) && (max($aBalls) - min($aBalls)) === 2) {
            $threeLinkSame = 0;
        } else {
            $threeLinkSame = empty($preData) ? 1 : $preData[count($data[$key1])][0] + 1;
        }
        $data[$key1][] = [$threeLinkSame];
    }

    public function generateLhcTrendData(& $data, $iNumType)
    {
        $sClip = ' ';
        $iCount = $this->iCount;
        $lastWinNumber = '';
        //[期号, winball, 总和，特码大小单双，特码升平降，总和大小单双，总和升平降，波色]
        foreach ($data as $key1 => $oIssue) {
            $data[$key1]['a0'] = $oIssue['issue'];
            $data[$key1]['a1'] = $oIssue['official_code'];
            $sWnNumber = explode($sClip, $oIssue['official_code']);
            if (empty($lastWinNumber)) {
                $lastIssue = LotteryIssue::where('lottery_id', 60)->where('issue', '<', $data[$key1]['a0'])->get([
                    'issue',
                    'official_code'
                ])->first();
                if ($lastIssue) {
                    $lastWinNumber = explode($sClip, $lastIssue->official_code);
                } else {
                    $lastWinNumber = array_fill(0, $this->iBallNum, 0);
                }
            }
            $winSum = array_sum($sWnNumber);
            $lastWinSum = array_sum($lastWinNumber);
            $data[$key1]['a2'] = $winSum;
            $tema = end($sWnNumber);
            $lastTema = end($lastWinNumber);
            $data[$key1]['a3'] = $tema > 24 ? 1 : 0;
            $data[$key1]['a4'] = ($tema % 2) ? 3 : 2;
            $data[$key1]['a5'] = ($tema == $lastTema) ? 2 : ($tema > $lastTema ? 1 : 3);//1升,2平,3降
            $data[$key1]['a6'] = $winSum > 174 ? 1 : 0;
            $data[$key1]['a7'] = ($winSum % 2) ? 3 : 2;
            $data[$key1]['a8'] = ($winSum == $lastWinSum) ? 2 : ($winSum > $lastWinSum ? 1 : 3);//1升,2平,3降
            $d = LotteryIssue::where('lottery_id', $oIssue['lottery_id'])->where('issue',
                $oIssue['issue'])->get(['official_open_time'])->first();
            $data[$key1]['a9'] = date('Y-m-d H:i:s', $d->official_open_time);
            $lastWinNumber = $sWnNumber;

        }
    }

    /**
     * 追号获取
     * @param $sLotteryId
     * @return mixed
     */
    static function getLotteryMap($sLotteryId)
    {
        $lotteryMap = Config::get('trend_map');
        foreach ($lotteryMap as $index => $series) {
            $active = 0;
            foreach ($series['children'] as $_i => $item) {
                if ($sLotteryId === $item['id']) {
                    $active = 1;
                    $lotteryMap[$index]['children'][$_i]['active'] = 1;
                } else {
                    $lotteryMap[$index]['children'][$_i]['active'] = 0;
                }
            }
            $lotteryMap[$index]['active'] = $active;
        }
        return json_encode($lotteryMap);
    }
}