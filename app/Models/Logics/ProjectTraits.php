<?php

namespace App\Models\Logics;

use App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel;
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\LotteryTrace;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

trait ProjectTraits
{

    /**
     * 前/后 台获取数据标准模板
     * @param  $condition
     * @return array
     */
    public static function getList($condition): array
    {
        $query = self::orderBy('id', 'desc');
        if (isset($condition['en_name'])) {
            $query->where('en_name', '=', $condition['en_name']);
        }
        $currentPage = isset($condition['page_index']) ? (int) $condition['page_index'] : 1;
        $pageSize = isset($condition['page_size']) ? (int) $condition['page_size'] : 15;
        $offset = ($currentPage - 1) * $pageSize;

        $total = $query->count();
        $menus = $query->skip($offset)->take($pageSize)->get();

        return [
            'data' => $menus,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => (int) ceil($total / $pageSize),
        ];
    }

    /**
     * @param $user
     * @param $lottery
     * @param $currentIssue
     * @param $data
     * @param $traceData
     * @param $inputDatas
     * @return array
     */
    public static function addProject($user, $lottery, $currentIssue, $data, $traceData, $inputDatas): array
    {
        $from = $inputDatas['from'] ?? 1; //手机端 还是 pc 端
        $returnData = [];
        foreach ($data as $_item) {
            $projectData = [
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
                'mode' => $_item['mode'],
                'times' => $_item['times'],
                'price' => $_item['price'],
                'total_cost' => $_item['total_price'],
                'bet_number' => $_item['code'],
                'issue' => $currentIssue->issue,
                'prize_set' => '',
                'ip' => Request::ip(),
                'proxy_ip' => json_encode(Request::ip()),
                'bet_from' => $from,
                'time_bought' => time(),
            ];
            $projectId = Project::create($projectData)->id;
            if ($traceData) {
                $traceMainData = [
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
                    'mode' => $_item['mode'],
                    'times' => $_item['times'],
                    'single_price' => $_item['price'],
                    'total_price' => $_item['total_price'],
                    'win_stop' => $inputDatas['trace_win_stop'],
                    'total_issues' => count($traceData),
                    'finished_issues' => 0,
                    'canceled_issues' => 0,
                    'start_issue' => key($traceData),
                    'now_issue' => '',
                    'end_issue' => array_key_last($traceData),
                    'stop_issue' => '',
                    'issue_process' => json_encode($traceData),
                    'add_time' => time(),
                    'stop_time' => 0,
                    'cancel_time' => 0,
                    'ip' => Request::ip(),
                    'proxy_ip' => json_encode(Request::ip()),
                    'day' => date('Ymd'),
                    'bet_from' => $from,
                ];
                // 保存追号主
                $traceId = LotteryTrace::create($traceMainData)->id;
                // 保存追号
                $i = 1;
                foreach ($traceData as $issue => $multiple) {
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
                            'ip' => Request::ip(),
                            'proxy_ip' => json_encode(Request::ip()),
                            'day' => date('Ymd'),
                            'bet_from' => $from,
                        ];
                        $_item['total_price'] += $traceListData['total_price'];
                        LotteryTraceList::create($traceListData);
                    }
                    $i++;
                }
            }
            $returnData['project'][] = [
                'id' => $projectId,
                'cost' => $_item['total_price'],
                'lottery_id' => $lottery->en_name,
                'method_id' => $_item['method_id'],
            ];
        }
        return $returnData;
    }

    /**
     * 开奖
     * @param $openNumber
     * @param $sWnNumber
     * @param $aPrized
     * @return bool|string
     */
    public function setWon($openNumber, $sWnNumber, $aPrized)
    {
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
                    $errorString = 'There have no prize for  Basic MethodId'.$iBasicMethodId.'leveldata'.json_encode($aPrizeOfBasicMethod);
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
    }

    /**
     * @param $openNumber
     * @param $sWnNumber
     * @param $iBasicMethodId
     * @return bool
     */
    public function setFail($openNumber, $sWnNumber = null, $iBasicMethodId = null): bool
    {
        try {
            DB::beginTransaction();
//            $lockProject = $this->lockForUpdate()->find($this->id);
            $this->status = self::STATUS_LOST;
            $data = [
                'basic_method_id' => $iBasicMethodId,
                'open_number' => $openNumber,
                'winning_number' => $this->formatWiningNumber($sWnNumber),
                'time_count' => now()->timestamp,
                'status' => self::STATUS_LOST,
            ];
            if ($this->update($data)) {
                DB::commit();
                $this->sendMoney();
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

    public function sendMoney(): void
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
                        Log::info('更新状态出错' . json_encode($this->errors()->first(), JSON_PRETTY_PRINT));
                    } else {
                        $res = true;
                        Log::info('Finished Send Money with bonus');
                    }
                } else {
                    $res = true;
                    Log::info('Finished Send Money with release frozen');
                }
            }
        } catch (Exception $e) {
            $res = false;
            Log::info('投注-异常:' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine()); //Clog::userBet
        }
        if ($res === true) {
            DB::commit();
        } else {
            DB::rollBack();
        }
    }

    /**
     * @param $sWnNumber
     * @return string|null
     */
    public function formatWiningNumber($sWnNumber = null):  ? string
    {
        return is_array($sWnNumber) ? implode('', $sWnNumber) : $sWnNumber;
    }
}
