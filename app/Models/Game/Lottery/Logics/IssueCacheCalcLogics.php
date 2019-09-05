<?php

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Support\Facades\Redis;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/31/2019
 * Time: 5:24 PM
 */
trait IssueCacheCalcLogics
{
    private static $redis_database = 3; //指定redis使用那个数据库，由于trait无法定义常量，所以为私有变量
    private static $code_range = 100; //指定存储的区间位数，现阶段最大支持100个开奖线路图
    private static $update_limit = 9; //低于此条数进行缓存尝试刷新
    /** =============== 功能函数 ============= */

    /**
     * 获取当前的奖期并且记录进缓存
     * @param  $lotteryIssue
     * @return mixed
     */
    public static function cacheRe(LotteryIssue $lotteryIssue)
    {
        $lotKey = $lotteryIssue->lottery_id;//lotKey
        $issue = $lotteryIssue->issue;//将期 sort
        //   $code = $lotteryIssue->official_code . ',' . $issue;//奖号 value = value . issue 保证唯一性,避免两期开出同样号码无法入库的情况

        //使用制定的redis库，便于管理
        Redis::command('select', [self::$redis_database]);
        $data = self::changeValue($lotteryIssue);
        //将期统一
        self::changeIssue($issue);

        //记录当前 使用有序集合存储开奖信息，开奖期号作为sort，彩票lottery_id作为集合名称 例如 hljssc 值为开奖号码
        Redis::zadd($lotKey, $issue, $data);

        //判断缓存容积，若单个彩种信息条数 = 1,代表第一次记录 进行缓存初始化，增加容错率 设定为>5 redis
        $count = Redis::zcard($lotKey);

        //初始化数据
        if ($count < self::$update_limit) {
            self::flushIssueCache($lotKey);
        }

        //切除多余数据
        if ($count > self::$code_range) {
            self::cutCacheData($lotKey, $count);
        }

        return true;
    }

    /**
     * 改变issue结构
     * @param  $issue 地址传递
     * @return mixed
     */
    public static function changeIssue(&$issue)
    {
        $issue = str_replace('-', '', $issue);
    }

    /**
     * 刷新指定彩种的缓存 补定到指定条数
     * @param  $lottery_id
     * @return mixed
     */
    public static function flushIssueCache($lottery_id): bool
    {
        $issues = LotteryIssue::where(['lottery_id' => $lottery_id, 'status_encode' => 1])
            ->orderBy('issue', 'desc')
            ->limit(self::$code_range)
            ->get();

        if (!count($issues)) {
            return false;
        }

        //写入缓存
        foreach ($issues as $issueValue) {
            $data = self::changeValue($issueValue);
            $issueKey = $issueValue->lottery_id;//issueKey
            $issue = $issueValue->issue;//将期 sort
            //奖期统一
            self::changeIssue($issue);
            Redis::zadd($issueKey, $issue, $data); //数据补齐
        }
        $count = Redis::zcard($lottery_id);
        if ($count > 100) {
            self::cutCacheData($lottery_id, $count);
        }
        return true;
    }

    /**
     * 切除多余的数据
     * @param  $lottery_id
     * @return bool
     */
    public static function cutCacheData($lottery_id, $count): bool
    {
        $sliceFlag = $count - self::$code_range - 1;
        return Redis::zremrangebyrank($lottery_id, 0, $sliceFlag);
    }

    /**
     * 切除多余的数据
     * @param  $value
     * @return mixed
     */
    public static function changeValue($value)
    {
        $returnData['open_time'] = $value->official_open_time;
        $returnData['issue'] = $value->issue;
        $returnData['code'] = $value->official_code;
        $lottery_id = $value->lottery_id;
        //六合彩不进行拆分
        if ($value->lottery_id === 'lhc') {
            $returnData['data'] = $returnData['code'];
            return json_encode($returnData);
        }
        //查找这个彩种的合法数字和他的分割符
        $lotteryList = LotteryList::where('en_name', $lottery_id)->first();
        $lotterySeries = LotterySerie::where('series_name', $lotteryList->series_id)->first();

        if ($lotterySeries->encode_splitter !== null) {
            $arrCode = explode($lotterySeries->encode_splitter, $returnData['code']);
        } else {
            $arrCode = str_split($returnData['code']);
        }
        //初始化数组 将期号码有多少位 有多少个子数组
        /*数组第一位是否遗漏 （这里不是连续遗漏 在获取的时候才是连续遗漏） 默认为1
         *第二位是当前列开的数字
         *第三为在这里与第一位相同，用户返回时标记当前是否漏开
         *  */
        $codeRange = explode(',', $lotteryList->valid_code);
        foreach ($codeRange as $codeRangeKey => $codeRangeValue) {
            $codeRange[$codeRangeKey] = intval($codeRangeValue);
        }
        $codeRange = array_flip($codeRange);
        ksort($codeRange);
        $secondData = array();
        foreach ($codeRange as $codeRangeK => $codeRangeV) {
            $secondData[$codeRangeK] = [1, 0, 1];
            unset($codeRangeV);
        }
        $resData = array();
        foreach ($arrCode as $arrCodeK => $arrCodeV) {
            $arrCodeV = (int)$arrCodeV;
            $resData[$arrCodeK] = $secondData;
            foreach ($resData[$arrCodeK] as $resDataArrCpdeK => $resDataArrCodeV) {
                $resData[$arrCodeK][$resDataArrCpdeK][0] = $arrCodeV === $resDataArrCpdeK ? 0 : 1;
                $resData[$arrCodeK][$resDataArrCpdeK][1] = $arrCodeV;
                $resData[$arrCodeK][$resDataArrCpdeK][2] = $resData[$arrCodeK][$resDataArrCpdeK][0];
                $resData[$arrCodeK][$resDataArrCpdeK][3] = $resData[$arrCodeK][$resDataArrCpdeK][0] === 0 ? 1 : 0;//记录连号
                unset($resDataArrCodeV);
            }
        }
        $returnData['data'] = $resData;
        return json_encode($returnData);
    }

    /**
     * 获取换成数据计算成符合前端的数据
     * @param  $lottery_id
     * @param  $range
     * @return mixed
     */
    public static function getTrend($lottery_id, $range)
    {
        $range--;
        Redis::command('select', [self::$redis_database]);
        //获取这个集合有多少
        $count = Redis::zcard($lottery_id);
        //初始化数据
        if ($count < self::$update_limit) {
            self::flushIssueCache($lottery_id);
        }

        //切除多余数据
        if ($count > self::$code_range) {
            self::cutCacheData($lottery_id, $count);
        }

        //确认数据存在
        $secCount = Redis::zcard($lottery_id);

        //没有数据返回空数据
        if ($secCount === 0) {
            return [];
        }

        //取出数据
        $redisData = Redis::zrevrange($lottery_id, 0, self::$code_range);

        //01 $resData = array();
        //查找这个彩种的合法数字和他的分割符
        $lotteryList = LotteryList::where('en_name', $lottery_id)->first();
        $lotterySeries = LotterySerie::where('series_name', $lotteryList->series_id)->first();

        if ($lotterySeries->encode_splitter !== null) {
            $arrCode = explode($lotterySeries->encode_splitter, json_decode($redisData[0])->code);
        } else {
            $arrCode = str_split(json_decode($redisData[0])->code);
        }
        $rowNumber = count($arrCode);
        $totalArr = self::createArray($rowNumber, $lotteryList);
        $redisCount = count($redisData);
        $trueRange = $redisCount > $range ? $range + 1 : $redisCount;
        $codeRangeCount = count(explode(',', $lotteryList->valid_code));
        //循环到指定的范围
        foreach ($redisData as $redisDataKey => $totalArrV) {
            /*
                                    if ($redisDataKey == 0)
                                        continue;
            */
            if ($redisDataKey > $range) {
                break;
            }
            //v是一次开奖的数据
            $redisData[$redisDataKey] = json_decode($totalArrV);
            $vdata = $redisData[$redisDataKey]->data;

            //循环单列
            foreach ($vdata as $vdataKey => $vdataValue) {
                $typeFlag = is_object($vdataValue);

                if ($typeFlag === false) {
                    $stdObj = new \stdClass();
                    foreach ($vdataValue as $vdataValueK => $vdataValueV) {
                        $stdObj->$vdataValueK = $vdataValueV;
                    }
                    $vdataValue = $stdObj;
                    $vdata[$vdataKey] = $stdObj;
                    $redisData[$redisDataKey]->data[$vdataKey] = $stdObj;
                }
                //上一层对应的位置 上一组开奖对应
                $preSite = $redisDataKey === 0 ? 0 : $redisData[$redisDataKey - 1]->data[$vdataKey];

                //偏移位 就是当前的中奖号码
                $moveKey = $vdataValue->{1}[1];
                //totalArr 统计数组中对应的位置
                $totalSite = $vdataKey * $codeRangeCount + $moveKey;

                //如果中了统计总位置加1
                $totalArr[0][$totalSite]++;
                foreach ($vdataValue as $vdataItemKey => $vdatavaItemValue) {
                    $site = $vdataKey * $rowNumber + $vdataItemKey;
                    /*最大遗漏*/
                    //记j录的漏号
                    $preMax = $totalArr[2][$site];
                    $preLxMax = $totalArr[3][$site];
                    //是开号 无需累加
                    if ($vdatavaItemValue[0] === 0) {
                        /*最大连出值*/
                        //最大连出值
                        $openFlag = $vdataValue->$vdataItemKey[2]; //当前是否开号

                        if ($openFlag === 0) {
                            //记录的最大值
                            $totalArr[3][$site];
                            //如果开号
                            //$currentLcz =  $vdataValue->$vdataItemKey[3]; //当前的连开值加上上一个的值
                            if ($redisDataKey !== 0) {
                                $vdataValue->$vdataItemKey[3] = $vdataValue->$vdataItemKey[3] + $preSite->$vdataItemKey[3];
                                if ($vdataValue->$vdataItemKey[3] > $preLxMax) {
                                    $totalArr[3][$site] = $vdataValue->$vdataItemKey[3];
                                }
                            }
                        }
                        continue;
                    }
                    //不开号
                    if ($vdatavaItemValue[0] !== 0) {
                        $totalArr[1][$site]++;
                    }

                    //不是开号当前遗漏期数等于他加他之前
                    if ($redisDataKey === 0) {
                        $beData = 0;
                    } else {
                        // dd($preSite);
                        $beData = $preSite->$vdataItemKey[0];
                    }

                    $vdataValue->$vdataItemKey[0] = $beData + 1;
                    $currentMax = $vdataValue->$vdataItemKey[0];
                    if ($currentMax > $preMax) {
                        $totalArr[2][$site] = $currentMax;
                    }
                }
            }
        }
        foreach ($totalArr[1] as $totalArrK => $totalArrV) {
            //遗漏值平均 公式为 彩票总期数减去历史中奖次数，得出历史遗漏总值，历史遗漏总值除以历史中奖次等于平均遗漏值。
            //历史中奖期数
            $history = $totalArr[0][$totalArrK];
            if ($history === 0) {
                continue;
            } else if ($trueRange - $history === 0) {
                $totalArr[1][$totalArrK] = 0;
            } else {
                $totalArr[1][$totalArrK] = ($trueRange - $history) / $history;
                if (is_float($totalArr[1][$totalArrK])) {
                    $totalArr[1][$totalArrK] = round($totalArr[1][$totalArrK], 2);
                }
            }
            unset($totalArrV);
        }

        $redisDataCount = count($redisData);

        if ($redisDataCount > $trueRange) {
            $redisData = array_slice($redisData, 0, $trueRange);
        }
        $resCalcData[] = $redisData;
        $resCalcData[] = $totalArr;
        return json_encode($resCalcData);
    }

    /*获取上层对应的位置*/


    /*创建指定列数的数组*/
    private static function createArray($rowNumber, $lotteryList)
    {
        $codeRange = explode(',', $lotteryList->valid_code);
        sort($codeRange);
        $startInde = (int)$codeRange[0];
        $lastValue = (int)$codeRange[count($codeRange) - 1];
        if ($startInde === 0) {
            $lastValue++;
        }
        //出现总次数
        $data[] = array_fill($startInde, $rowNumber * $lastValue, 0);
        //平均遗漏值
        $data[] = array_fill($startInde, $rowNumber * $lastValue, 0);
        //最大遗漏值
        $data[] = array_fill($startInde, $rowNumber * $lastValue, 0);
        //最大连出值
        $data[] = array_fill($startInde, $rowNumber * $lastValue, 0);
        return $data;
    }
}