<?php

namespace App\Models\Logics;

use App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel;
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\LotteryTrace;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;
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
        $currentPage = isset($condition['page_index']) ? (int)$condition['page_index'] : 1;
        $pageSize = isset($condition['page_size']) ? (int)$condition['page_size'] : 15;
        $offset = ($currentPage - 1) * $pageSize;

        $total = $query->count();
        $menus = $query->skip($offset)->take($pageSize)->get();

        return [
            'data' => $menus,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => (int)ceil($total / $pageSize)
        ];
    }

    /**
     * 获取投注页需要的注单数据
     * @param $lotterySign
     * @param  int  $start
     * @param  int  $count
     * @return array
     */
    public static function getGamePageList($lotterySign, $start = 0, $count = 10): array
    {
        if ($count > 100) {
            $count = 100;
        }
        $projectData = [];
        $where = $lotterySign === '*' ? ['lottery_sign' => ['!=', null]] : [];
        $projectList = self::orderBy('id', 'desc')->where(static function ($query) use ($lotterySign) {
            if ($lotterySign !== '*') {
                $query->where('lottery_sign', '=', $lotterySign);
            }
        })->skip($start)->take($count)->get();
        foreach ($projectList as $item) {
            $projectData[] = [
                'id' => $item->id,
                'lottery_name' => $item->lottery_sign,
                'method_name' => $item->method_name,
                'issue' => $item->issue,
                'open_codes' => $item->open_number,
                'bet_codes' => $item->bet_number,
                'total_cost' => $item->total_cost,
                'single_price' => $item->price,
                'bonus' => $item->bonus,
                'prize_group' => $item->bet_prize_group,
                'status' => $item->status,
            ];
        }
        $traceData = [];
        $traceList = LotteryTrace::orderBy('id', 'desc')->where('lottery_sign', '=',
            $lotterySign)->skip($start)->take($count)->get();
        foreach ($traceList as $item) {
            $traceData[] = [
                'id' => $item->id,
                'lottery_name' => $item->lottery_sign,
                'method_name' => $item->method_name,
                'start_issue' => $item->start_issue,
                'process' => $item->issue_process,
                'total_cost' => $item->total_price,
                'total_price' => $item->total_prize,
                'is_win_stop' => $item->win_stop,
                'status' => $item->status,
            ];
        }
        return [
            'project' => $projectData,
            'trace' => $traceData,
        ];
    }

    /**
     * @param $user
     * @param $lottery
     * @param $currentIssue
     * @param $data
     * @param $traceData
     * @param  int  $from
     * @return array
     */
    public static function addProject($user, $lottery, $currentIssue, $data, $traceData, $from = 1): array
    {
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
                    'method_name' => $_item['method_name'],
                    'bet_number' => $_item['code'],
                    'user_prize_group' => $user->prize_group,
                    'bet_prize_group' => $_item['prize_group'],
                    'mode' => $_item['mode'],
                    'times' => $_item['times'],
                    'single_price' => $_item['price'],
                    'total_price' => $_item['total_price'],
                    'total_issues' => count($traceData),
                    'finished_issues' => 0,
                    'canceled_issues' => 0,
                    'start_issue' => $traceData[key($traceData)],
                    'now_issue' => '',
                    'end_issue' => $traceData[array_key_last($traceData)],
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
                foreach ($traceData as $issue => $multiple) {
                    foreach ($data as $dataItem) {
                        $traceListData = [
                            'user_id' => $user->id,
                            'username' => $user->username,
                            'top_id' => $user->top_id,
                            'rid' => $user->rid,
                            'parent_id' => $user->parent_id,
                            'is_tester' => $user->is_tester,
                            'series_id' => $lottery->series_id,
                            'lottery_sign' => $lottery->en_name,
                            'method_sign' => $dataItem['method_id'],
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
                        LotteryTraceList::create($traceListData);
                    }
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
        foreach ($aPrized as $iBasicMethodId => $aPrizeOfBasicMethod) {
            $iLevel = key($aPrizeOfBasicMethod);
            $iCount = current($aPrizeOfBasicMethod);
            $PrizeEloq = LotteryMethodsWaysLevel::where([
                ['basic_method_id', '=', $iBasicMethodId],
                ['level', '=', $iLevel]
            ])->first();
            if ($iCount > 0) {
                $bonus = $this->bet_prize_group * $PrizeEloq->prize / 1800;
                $bonus *= $this->mode * $this->times * $iCount;
                if ($this->price === 1) {
                    $bonus /= 2;
                }
                $totalBonus += $bonus;
                $data = [
                    'basic_method_id' => $iBasicMethodId,
                    'open_number' => $openNumber,
                    'winning_number' => $sWnNumber,
                    'level' => $iLevel,
                    'bonus' => $totalBonus,
                    'is_win' => 1,
                    'time_count' => now()->timestamp,
                    'status' => self::STATUS_WON
                ];
                try {
                    DB::beginTransaction();
                    $lockProject = $this->lockForUpdate()->find($this->id);
                    $lockProject->update($data);
                    DB::commit();
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
            $lockProject = $this->lockForUpdate()->find($this->id);
            $this->status = $lockProject->status = self::STATUS_LOST;
            $data = [
                'basic_method_id' => $iBasicMethodId,
                'open_number' => $openNumber,
                'winning_number' => $sWnNumber,
                'time_count' => now()->timestamp,
                'status' => self::STATUS_LOST
            ];
            $lockProject->update($data);
            if ($lockProject->save()) {
                DB::commit();
            } else {
                $strError = json_encode($lockProject->errors(), JSON_PRETTY_PRINT);
                Log::channel('issues')->info($strError);
            }
        } catch (Exception $e) {
            Log::channel('issues')->info($e->getMessage());
            return false;
            DB::rollBack();
        }
        return true;
    }
}
