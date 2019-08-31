<?php

namespace App\Models\Logics;

use App\Models\Game\Lottery\LotteryPrizeGroup;
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\LotteryTrace;
use App\Models\Project;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

trait ProjectTraits
{

    /**
     * @param $user
     * @param $lottery
     * @param $currentIssue
     * @param $data
     * @param $inputDatas
     * @param  int  $from  手机端 还是 pc 端
     * @return array
     */
    public static function addProject($user, $lottery, $currentIssue, $data, $inputDatas, $from = 1): array
    {
        $traceFirstMultiple = 1;
        $isTrace = 0;
        $traceData = [];
        if (isset($inputDatas['is_trace'])) {
            $isTrace = (int) $inputDatas['is_trace'];
            if ($isTrace === 1 && count($inputDatas['trace_issues']) > 1) {
                // 追号期号
                $arrTraceKeys = array_keys($inputDatas['trace_issues']);
                $traceDataCollection = $lottery->checkTraceData($arrTraceKeys);
                if (count($arrTraceKeys) !== $traceDataCollection->count()) {
                    $arr['error'] = '100309';
                    return $arr;
                }
                $traceFirstMultiple = Arr::first($inputDatas['trace_issues']);
                // $traceData = array_slice($inputDatas['trace_issues'], 1, null, true);
                $traceData = $inputDatas['trace_issues'];
            } elseif ($isTrace === 0) {
                // 投注期号是否正确
                if ($currentIssue->issue !== key($inputDatas['trace_issues'])) {
                    $arr['error'] = '100310';
                    return $arr;
                }
            }
        }
        $returnData = [];
        foreach ($data as $_item) {
            $project = self::saveSingleProject(
                $user,
                $lottery,
                $_item,
                $inputDatas,
                $isTrace,
                $traceFirstMultiple,
                $currentIssue,
                $from
            );
            if ($traceData) {
                self::saveTrace(
                    $project,
                    $user,
                    $lottery,
                    $data,
                    $traceData,
                    $_item,
                    $inputDatas,
                    $from,
                );
            }
            $returnData['project'][] = [
                'id' => $project->id,
                'cost' => $_item['total_price'],
                'lottery_id' => $lottery->en_name,
                'method_id' => $_item['method_id'],
            ];
        }
        return $returnData;
    }

    /**
     * @param $user
     * @param $lottery
     * @param $_item
     * @param $inputDatas
     * @param $isTrace
     * @param $traceFirstMultiple
     * @param $currentIssue
     * @param $from
     * @return mixed
     */
    public static function saveSingleProject(
        $user,
        $lottery,
        $_item,
        $inputDatas,
        $isTrace,
        $traceFirstMultiple,
        $currentIssue,
        $from
    ) {
        $bresult = LotteryPrizeGroup::makePrizeSettingArray(
            $_item['method_id'],
            self::DEFAULT_PRIZE_GROUP,
            $lottery->series_id,
            $aPrizeSettings,
            $aPrizeSettingOfWay,
            $aMaxPrize
        );
        if ($bresult) {
            die('奖金组错误');
        }
        $projectData = [
            'serial_number' => self::getProjectSerialNumber(),
            'user_id' => $user->id,
            'username' => $user->username,
            'top_id' => $user->top_id,
            'rid' => $user->rid,
            'parent_id' => $user->parent_id,
            'is_tester' => $user->is_tester,
            'series_id' => $lottery->series_id,
            'lottery_sign' => $lottery->en_name,
            'method_sign' => $_item['method_id'],
            'method_name' => $_item['method_name'],
            'method_group' => $_item['method_group'],
            'user_prize_group' => $user->prize_group,
            'bet_prize_group' => $_item['prize_group'],
            'prize_set' => json_encode($aPrizeSettingOfWay),
            'mode' => $_item['mode'],
            'times' => $isTrace === 1 && count(
                $inputDatas['trace_issues']
            ) > 0 ? $_item['times'] * $traceFirstMultiple : $_item['times'],
            'price' => $isTrace === 1 && count(
                $inputDatas['trace_issues']
            ) > 0 ? $_item['price'] * $traceFirstMultiple : $_item['price'],
            'total_cost' => $_item['total_price'],
            'bet_number' => $_item['code'],
            'issue' => $currentIssue->issue,
            'ip' => Request::ip(),
            'proxy_ip' => json_encode(Request::ip()),
            'bet_from' => $from,
            'time_bought' => time(),
        ];
        return Project::create($projectData);
    }

    /**
     * @return string
     */
    public static function getProjectSerialNumber(): string
    {
        return 'XW' . Str::orderedUuid()->getNodeHex();
    }

    /**
     * @param $project
     * @param $user
     * @param $lottery
     * @param $data
     * @param $traceData
     * @param $_item
     * @param $inputDatas
     * @param $from
     */
    public static function saveTrace(
        $project,
        $user,
        $lottery,
        $data,
        $traceData,
        $_item,
        $inputDatas,
        $from
    ): void {
        LotteryPrizeGroup::makePrizeSettingArray(
            $_item['method_id'],
            self::DEFAULT_PRIZE_GROUP,
            $lottery->series_id,
            $aPrizeSettings,
            $aPrizeSettingOfWay,
            $aMaxPrize
        );
        $traceMainData = [
            'trace_serial_number' => self::getProjectSerialNumber(),
            'user_id' => $user->id,
            'username' => $user->username,
            'top_id' => $user->top_id,
            'rid' => $user->rid,
            'parent_id' => $user->parent_id,
            'is_tester' => $user->is_tester,
            'series_id' => $lottery->series_id,
            'lottery_sign' => $lottery->en_name,
            'method_sign' => $_item['method_id'],
            'method_group' => $_item['method_group'],
            'method_name' => $_item['method_name'],
            'bet_number' => $_item['code'],
            'user_prize_group' => $user->prize_group,
            'bet_prize_group' => $_item['prize_group'],
            'prize_set' => json_encode($aPrizeSettingOfWay),
            'mode' => $_item['mode'],
            'times' => $_item['times'],
            'single_price' => $_item['price'],
            'total_price' => $_item['total_price'],
            'win_stop' => $inputDatas['trace_win_stop'],
            'total_issues' => count($traceData),
            'finished_issues' => 0,
            'canceled_issues' => 0,
            'start_issue' => key($traceData),
            'now_issue' => $project->issue,
            'end_issue' => array_key_last($traceData),
            'stop_issue' => '',
            'issue_process' => json_encode($traceData),
            'add_time' => time(),
            'stop_time' => 0,
            'ip' => Request::ip(),
            'proxy_ip' => json_encode(Request::ip()),
            'bet_from' => $from,
        ];
        // 保存追号主
        $traceId = LotteryTrace::create($traceMainData)->id;
        // 保存追号
        $i = 1;
        foreach ($traceData as $issue => $multiple) {
            if ($i === 1) {
                $project_serial_number = $project->serial_number;
                $status = LotteryTraceList::STATUS_RUNNING;
            } else {
                $project_serial_number = null;
                $status = LotteryTraceList::STATUS_WAITING;
            }
            foreach ($data as $dataItem) {
                $traceListData = [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'top_id' => $user->top_id,
                    'rid' => $user->rid,
                    'trace_id' => $traceId,
                    'order_queue' => $i,
                    'parent_id' => $user->parent_id,
                    'is_tester' => $user->is_tester,
                    'series_id' => $lottery->series_id,
                    'project_id' => $project->id,
                    'project_serial_number' => $project_serial_number,
                    'lottery_sign' => $lottery->en_name,
                    'method_sign' => $dataItem['method_id'],
                    'method_group' => $_item['method_group'],
                    'method_name' => $dataItem['method_name'],
                    'issue' => $issue,
                    'bet_number' => $dataItem['code'],
                    'mode' => $dataItem['mode'],
                    'times' => $dataItem['times'] * $multiple,
                    'single_price' => $dataItem['price'],
                    'total_price' => $dataItem['total_price'] * $multiple,
                    'user_prize_group' => $user->prize_group,
                    'bet_prize_group' => $dataItem['prize_group'],
                    'prize_set' => json_encode($aPrizeSettingOfWay),
                    'ip' => Request::ip(),
                    'proxy_ip' => json_encode(Request::ip()),
                    'bet_from' => $from,
                    'status' => $status,
                ];
                $_item['total_price'] += $traceListData['total_price'];
                LotteryTraceList::create($traceListData);
            }
            $i++;
        }
    }

    public function setWon(
        $openNumber,
        $sWnNumber,
        $aPrized
    ) {
        $arrBasicMethodId=[];
        $arrLevel=[];
        $totalBonus = 0;
        $totalCount = 0;
        $aPrizeSet = json_decode($this->prize_set, true);
        foreach ($aPrized as $iBasicMethodId => $aPrizeOfBasicMethod) {
            foreach ($aPrizeOfBasicMethod as $iLevel => $iCount) {
                if ($iBasicMethodId === 123) {
                    $win = explode(' ', $sWnNumber);
                    $tema = end($win);
                    if ($tema === '49') {
                        $prizeToClaim = 1;
                    } else {
                        $prizeToClaim = $aPrizeSet[$iBasicMethodId][$iLevel];
                    }
                } else {
                    $prizeToClaim = $aPrizeSet[$iBasicMethodId][$iLevel];
                }
                if ($prizeToClaim !== null) {
                    if ($iCount > 0) {
                        $bonus = $this->bet_prize_group * $prizeToClaim / 1800;
                        $bonus *= $this->mode * $this->times * $iCount;
                        if (pack('f', $this->price) === pack('f', 1.0)) {
                            $bonus /= 2;
                        }
                        $totalCount += $iCount;
                        $totalBonus += $bonus;
                        $arrLevel[] = $iLevel;
                        $arrBasicMethodId[]= $iBasicMethodId;
                    } else {
                        $errorString = 'There have no Count:' . $iBasicMethodId . ' level:' . $iLevel . ' Count:' . $iCount;
                        Log::channel('issues')->info($errorString);
                    }
                } else {
                    $levelDataNote = 'leveldata' . json_encode($aPrizeOfBasicMethod);
                    $errorString = 'There have no prize for  Basic MethodId' . $iBasicMethodId . $levelDataNote;
                    Log::channel('issues')->error($errorString);
                }
            }
        }
        if ($totalCount > 0) {
            $data = [
                'basic_method_id' => implode(',', $arrBasicMethodId),
                'open_number' => $openNumber,
                'winning_number' => $this->formatWiningNumber($sWnNumber),
                'level' => implode(',', $arrLevel), //@todo may be with string to concact
                'bonus' => $totalBonus,
                'is_win' => 1,
                'time_count' => now()->timestamp,
                'status' => self::STATUS_WON,
            ];
            try {
                DB::beginTransaction();
                $this->update($data); //@todo maybe only a time update
                DB::commit();
                $this->sendMoney();
                return true;
            } catch (Exception $e) {
                Log::channel('issues')->info($e->getMessage());
                DB::rollBack();
                return $e->getMessage();
            }
        } else {
            return $this->setFail($openNumber, $sWnNumber);
        }
    }

    /**
     * 开奖
     * @param $openNumber
     * @param $sWnNumber
     * @param $aPrized
     * @return bool|string
     */
    /*public function setWon(
    $openNumber,
    $sWnNumber,
    $aPrized
    ) {
    $totalBonus = 0;
    $totalCount = 0;
    $totalLevel = 0;
    $finalLevel = 0;
    foreach ($aPrized as $iBasicMethodId => $aPrizeOfBasicMethod) {
    foreach ($aPrizeOfBasicMethod as $iLevel => $iCount) {
    $totalCount += $iCount;
    $PrizeEloq = LotteryMethodsWaysLevel::where([
    ['basic_method_id', '=', $iBasicMethodId],
    ['level', '=', $iLevel],
    ['method_id', '=', $this->method_sign]
    ])->first();
    if ($PrizeEloq !== null) {
    if ($iCount > 0) {
    $bonus = $this->bet_prize_group * $PrizeEloq->prize / 1800;
    $bonus *= $this->mode * $this->times * $iCount;
    if (pack('f', $this->price) === pack('f', 1.0)) {
    $bonus /= 2;
    }
    $totalBonus += $bonus;
    $finalLevel = $iLevel;
    $totalLevel++;
    } else {
    $errorString = 'There have no Count:'.$iBasicMethodId.' level:'.$iLevel.' Count:'.$iCount;
    Log::channel('issues')->info($errorString);
    }
    } else {
    $levelDataNote = 'leveldata'.json_encode($aPrizeOfBasicMethod);
    $errorString = 'There have no prize for  Basic MethodId'.$iBasicMethodId.$levelDataNote;
    Log::channel('issues')->error($errorString);
    }
    }
    if ($totalCount > 0) {
    $data = [
    'basic_method_id' => $iBasicMethodId,
    'open_number' => $openNumber,
    'winning_number' => $this->formatWiningNumber($sWnNumber),
    'level' => $finalLevel,//@todo may be with string to concact
    'bonus' => $totalBonus,
    'is_win' => 1,
    'time_count' => now()->timestamp,
    'status' => self::STATUS_WON,
    ];
    try {
    DB::beginTransaction();
    //                    $lockProject = $this->lockForUpdate()->find($this->id);
    $this->update($data);//@todo maybe only a time update
    DB::commit();
    $this->sendMoney();
    } catch (Exception $e) {
    Log::channel('issues')->info($e->getMessage());
    DB::rollBack();
    return $e->getMessage();
    }
    } else {
    $this->setFail($openNumber, $sWnNumber, $iBasicMethodId);
    }
    return true;
    }
    }*/

    /**
     * @param $sWnNumber
     * @return string|null
     */
    public function formatWiningNumber(
        $sWnNumber = null
    ):  ? string {
        return is_array($sWnNumber) ? implode('', $sWnNumber) : $sWnNumber;
    }

    public function sendMoney() : void
    {
        $params = [
            'amount' => $this->bonus,
            'frozen_release' => $this->total_cost,
            'user_id' => $this->user_id,
            'project_id' => $this->id,
            'lottery_id' => $this->lottery_sign,
            'method_id' => $this->method_sign,
            'issue' => $this->issue,
        ];
        $account = $this->account;
        DB::beginTransaction();
        try {
            $res = $account->operateAccount($params, 'game_bonus');
            if ($res !== true) {
                Log::info($res);
            } else {
                $oProject = self::find($this->id);
                if ($oProject->status === Project::STATUS_WON) {
                    $oProject->status = Project::STATUS_PRIZE_SENT;
                    $oProject->time_prize = now()->timestamp;
                    $oProject->save();
                    if (!empty($this->errors()->first())) {
                        $res = false;
                        $info = '更新状态出错' . json_encode($this->errors()->first(), JSON_PRETTY_PRINT);
                        Log::channel('calculate-prize')->info($info);
                    } else {
                        $res = true;
                        Log::channel('calculate-prize')->info('Finished Send Money with bonus');
                    }
                } else {
                    $res = true;
                    Log::channel('calculate-prize')->info('Finished Send Money with release frozen');
                }
            }
        } catch (Exception $e) {
            $res = false;
            $info = '投注-异常:' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine();
            Log::channel('calculate-prize')->info($info); //Clog::userBet
        }
        if ($res === true) {
            DB::commit();
        } else {
            DB::rollBack();
        }
    }

    /**
     * @param $openNumber
     * @param $sWnNumber
     * @return bool
     */
    public function setFail(
        $openNumber,
        $sWnNumber = null
    ): bool {
        try {
            DB::beginTransaction();
//            $lockProject = $this->lockForUpdate()->find($this->id);
            $this->status = self::STATUS_LOST;
            $data = [
//                'basic_method_id' => $iBasicMethodId,
                'open_number' => $openNumber,
                'winning_number' => $this->formatWiningNumber($sWnNumber),
                'time_count' => now()->timestamp,
                'status' => self::STATUS_LOST,
            ];
            if ($this->update($data)) {
                DB::commit();
                // $this->sendMoney();
            } else {
                $strError = json_encode($this->errors()->first(), JSON_PRETTY_PRINT);
                Log::channel('issues')->info($strError);
            }
        } catch (Exception $e) {
            Log::channel('issues')->info($e->getMessage());
            DB::rollBack();
            return false;
        }
        return true;
    }
}
