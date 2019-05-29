<?php

namespace App\Models\Traits;

use App\Models\Trace;
use Illuminate\Support\Facades\DB;
/**
 * @Author: LingPh
 * @Date:   2019-05-29 17:44:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-29 17:49:21
 */
trait ProjectTraits
{

    /**
     * 前/后 台获取数据标准模板
     * @param $condition
     * @return array
     */
    public static function getList($condition)
    {
        $query = self::orderBy('id', 'desc');
        if (isset($condition['en_name'])) {
            $query->where('en_name', '=', $condition['en_name']);
        }

        $currentPage = isset($condition['page_index']) ? intval($condition['page_index']) : 1;
        $pageSize = isset($condition['page_size']) ? intval($condition['page_size']) : 15;
        $offset = ($currentPage - 1) * $pageSize;

        $total = $query->count();
        $menus = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $menus, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    /**
     * 获取投注页需要的注单数据
     * @param $lotterySign
     * @param int $start
     * @param int $count
     * @return array
     */
    public static function getGamePageList($lotterySign, $start = 0, $count = 10)
    {
        if ($count > 100) {
            $count = 100;
        }
        $projectData = [];
        $projectList = self::orderBy('id', 'desc')->where('lottery_sign', '=', $lotterySign)->skip($start)->take($count)->get();
        foreach ($projectList as $item) {
            $projectData[] = [
                'id' => $item->id,
                'lottery_name' => $item->lottery_sign,
                'method_name' => $item->method_name,
                'issue' => $item->issue,
                'open_codes' => $item->open_number,
                'bet_codes' => $item->bet_number,
                'total_cost' => $item->cost,
                'prize' => $item->total_prize,
                'prize_group' => $item->bet_prize_group,
                'status' => $item->status,
            ];
        }
        $traceData = [];
        $traceList = Trace::orderBy('id', 'desc')->where('lottery_sign', '=', $lotterySign)->skip($start)->take($count)->get();
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
     * @param int $from
     * @return array
     */
    public static function addProject($user, $lottery, $currentIssue, $data, $traceData, $from = 1)
    {
        $returnData = [];
        $traceMainData = [];
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
                'method_sign' => $_item["method_id"],
                'method_name' => $_item["method_name"],
                'user_prize_group' => $user->prize_group,
                'bet_prize_group' => $_item['prize_group'],
                'mode' => $_item['mode'],
                'times' => $_item['times'],
                'price' => $_item['price'],
                'total_cost' => $_item['total_price'],
                'bet_number' => $_item['code'],
                'issue' => $currentIssue->issue,

                'prize_set' => '',

                'ip' => real_ip(),
                'proxy_ip' => real_ip(),

                'bet_from' => $from,
                'time_bought' => time(),
            ];

            if ($traceData) {
                $traceMainData[] = [
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

                    'start_issue' => $traceData[1],
                    'now_issue' => '',
                    'end_issue' => $traceData[count($traceData) - 1],
                    'stop_issue' => '',
                    'issue_process' => json_encode($traceData),

                    'add_time' => time(),
                    'stop_time' => 0,
                    'cancel_time' => 0,

                    'ip' => real_ip(),
                    'proxy_ip' => real_ip(),

                    'day' => date('Ymd'),
                    'bet_from' => $from,
                ];
            }
            $id = DB::table('projects')->insertGetId($projectData);
            $returnData['project'][] = [
                'id' => $id,
                'cost' => $_item['total_price'],
                'lottery_id' => $lottery->en_name,
                'method_id' => $_item['method_id'],
            ];
        }
        // 保存追号主
        if ($traceMainData) {
            DB::table('traces')->insert($traceMainData);
        }
        // 保存追号
        $traceListData = [];
        foreach ($traceData as $issue => $mark) {
            foreach ($data as $_item) {
                $traceListData[] = [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'top_id' => $user->top_id,
                    'rid' => $user->rid,
                    'parent_id' => $user->parent_id,
                    'is_tester' => $user->is_tester,
                    'series_id' => $lottery->series_id,
                    'lottery_sign' => $lottery->en_name,
                    'method_sign' => $_item["method_id"],
                    'method_name' => $_item["method_name"],
                    'issue' => $issue,
                    'bet_number' => $_item['code'],
                    'mode' => $_item['mode'],
                    'times' => $_item['times'],
                    'single_price' => $_item['price'],
                    'total_price' => $_item['total_price'],

                    'user_prize_group' => $user->prize_group,
                    'bet_prize_group' => $_item['prize_group'],
                    'bet_number' => $_item['code'],
                    'ip' => real_ip(),
                    'proxy_ip' => real_ip(),
                    'day' => date("Ymd"),
                    'bet_from' => $from,
                ];
            }
        }
        DB::table('trace_list')->insert($traceListData);
        return $returnData;
    }

    // 开奖
    public function open($openCode)
    {
        $project = $this;
        $lottery = Lottery::getLottery($project->lottery_id);
        $methodArr = $lottery->getMethod($project->method_id);
        $oMethod = $methodArr['object'];

        $openCodeArr = $lottery->formatOpenCode($openCode);
        $result = $oMethod->assert($project->bet_number, $openCodeArr);
        $totalBonus = 0;
        if ($result) {
            foreach ($result as $level => $count) {
                $levelConfig = $oMethod->levels;
                if (isset($levelConfig[$level])) {
                    $prize = $levelConfig[$level]['prize'];
                    $bonus = 2000 * $project->bet_prize_group / $prize;
                    $bonus = $bonus * $count * $project->times * $project->mode;
                    if ($project->single_price == 1) {
                        $bonus = $bonus / 2;
                    }
                    $totalBonus += $bonus;
                }
            }
        }
        $project->status_count = 1;
        $project->time_count = time();
        $project->status_prize = 1;
        $project->time_prize = time();
        if ($totalBonus > 0) {
            $project->is_win = 1;
            $project->bonus = $totalBonus;
            $project->save();
            return [
                'user_id' => $project->user_id,
                'project_id' => $project->id,
                'lottery_id' => $project->lottery_id,
                'method_id' => $project->method_id,
                'issue' => $project->issue,
                'amount' => $totalBonus,
            ];
        }
        $project->save();
        return [];
    }
}
