<?php

namespace App\Models\Game\Lottery\Logics\LotteryTrend;

use Illuminate\Support\Facades\Cache;

trait LotteryTrendSsc
{
    //近xx期计算结果，包含出现总次数，平均遗漏值，最大遗漏值，最大连出值等信息
    //近30期计算结果
    private static $thrty = 30;
    private static $thrtyw;
    private static $thrtyq;
    private static $thrtyb;
    private static $thrtys;
    private static $thrtyg;

    //近50期计算结果
    private static $fifty = 50;
    private static $fiftyw;
    private static $fiftyq;
    private static $fiftyb;
    private static $fiftys;
    private static $fiftyg;

    //近100期计算结果
    private static $hundredy = 100;
    private static $hundredyw;
    private static $hundredyq;
    private static $hundredyb;
    private static $hundredys;
    private static $hundredyg;

    private static $issue;
    private static $officialCode;
    private static $officialCodeArr;

    private static $currentCode;//当前号码分析
    private static $statistics;//当前号码统计数据

    /**
     * 彩种趋势记录-ssc
     * @param $issue
     * @param $lotteryIssue
     */
    public static function trend($issue, $officialCode)
    {
        self::$issue = $issue;
        self::$officialCode = $officialCode;
        self::$officialCodeArr = str_split($officialCode);
        self::currentCode();

        //近30期数
        self::trendLastThirty();

        //近50期数
        self::trendLastFifty();

        //近100期数
        self::trendLastHundred();
    }

    /**
     * 近30期数
     */
    private static function trendLastThirty()
    {
        //五星
        self::fiveStar(self::$thrty);
        //四星

        //前三

        //中三

        //后三

        //前二

        //后二
    }


    /**
     * 近50期数
     */
    private static function trendLastFifty()
    {
    }


    /**
     * 近100期数
     */
    private static function trendLastHundred()
    {
    }

    /**
     * 五星
     */
    private static function fiveStar($num)
    {
        $redisKey = 'trend_fivestar_' . $num;
        $history = Cache::get($redisKey);

        $dataList = [];

        if (empty($history)) {
            $dataList['data'] = self::$currentCode;
            $dataList['statistics'] = self::$statistics;

            Cache::put($redisKey, self::$currentCode);
            return;
        }


        array_unshift($history, self::$currentCode);
        if (count($history) > $num) {
            //
        }

        //处理当前信息和最后一条信息
//        foreach ($history as $period) {
//
//        }
    }

    /**
     * 四星
     */
    private static function fourStar($num)
    {
    }

    /**
     * 前三
     */
    private static function frondThree($num)
    {
    }

    /**
     * 中三
     */
    private static function midThree($num)
    {
    }

    /**
     * 中三
     */
    private static function lastThree($num)
    {
    }

    /**
     * 前二
     */
    private static function frondTwo($num)
    {
    }


    /**
     * 后二
     */
    private static function lastTwo($num)
    {
    }


    private static function currentCode()
    {
        $analysisCode = [];
        foreach (self::$officialCodeArr as $code) {
            for ($i = 0; $i <= 9; $i++) {
                if (!isset($analysisCode[$i])) {
                    $analysisCode[$i][0] = 1;
                    $analysisCode[$i][1] = 0;
                }
                if ($code == $i) {
                    $analysisCode[$i][0] = 0;
                    $analysisCode[$i][1] += 1;
                }
            }
        }

        self::$currentCode = [
            self::$issue,
            self::$officialCode,
            self::analysisCode(self::$officialCodeArr[0]),
            self::analysisCode(self::$officialCodeArr[1]),
            self::analysisCode(self::$officialCodeArr[2]),
            self::analysisCode(self::$officialCodeArr[3]),
            self::analysisCode(self::$officialCodeArr[4]),
            $analysisCode
        ];
    }


    /**
     * 号码分析
     */
    private static function analysisCode($number)
    {
        $code = [];
        for ($i = 0; $i <= 9; $i++) {
            $code[$i] = 1;
            if ($number == $i) {
                $code[$i] = 0;
            }
        }
        return $code;
    }
}
