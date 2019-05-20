<?php

namespace App\Http\Controllers\FrontendApi\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\models\IssueModel;
use App\models\LotteriesModel;
use App\models\MethodsModel;
use App\models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class LottriesController extends FrontendApiMainController
{
    public function lotteryList(): JsonResponse
    {
        $lotteries = LotteriesModel::with(['issueRule:lottery_id,begin_time,end_time'])->get([
            'cn_name as name',
            'en_name',
            'series_id',
            'min_times',
            'max_times',
            'valid_modes',
            'min_prize_group',
            'max_prize_group',
            'max_trace_number',
            'day_issue',
        ]);
        $seriesConfig = config('game.main.series');
        $data = [];
        foreach ($lotteries as $lottery) {
            if (!isset($data[$lottery->series_id])) {
                $data[$lottery->series_id] = [
                    'name' => $seriesConfig[$lottery->series_id],
                    'sign' => $lottery->series_id,
                    'list' => []
                ];
            }
            $data[$lottery->series_id]['list'][] = [
                'id' => $lottery->en_name,
                'name' => $lottery->name,
                'min_times' => $lottery->min_times,
                'max_times' => $lottery->max_times,
                'valid_modes' => $lottery->valid_modes,
                'min_prize_group' => $lottery->min_prize_group,
                'max_prize_group' => $lottery->max_prize_group,
                'max_trace_number' => $lottery->max_trace_number,
                'day_issue' => $lottery->day_issue,
                'begin_time' => $lottery->issueRule['begin_time'],
                'end_time' => $lottery->issueRule['end_time'],
            ];
        }
        return $this->msgOut(true, $data);
    }

    public function lotteryInfo(): JsonResponse
    {
        $lotteries = LotteriesModel::where('status', 1)->get();
        $cacheData = [];
        $redisKey = 'frontend.lottery.lotteryInfo';
        if (Cache::has($redisKey)) {
            $cacheData = Cache::get($redisKey);
        } else {
            foreach ($lotteries as $lottery) {
                $lottery->valid_modes = $lottery->getFormatMode();

                // 获取所有玩法
                $methods = MethodsModel::getMethodConfig($lottery->en_name);
                $methodData = [];

                $groupName = config('game.method.group_name');
                $rowName = config('game.method.row_name');

                $rowData = [];
                foreach ($methods as $index => $method) {
                    $rowData[$method->method_group][$method->method_row][] = [
                        'method_name' => $method->method_name,
                        'method_id' => $method->method_id
                    ];
                }

                $groupData = [];
                $hasRow = [];
                foreach ($methods as $index => $method) {
                    // 行
                    if (!isset($groupData[$method->method_group])) {
                        $groupData[$method->method_group] = [];
                    }

                    if (!isset($hasRow[$method->method_group]) || !in_array($method->method_row,
                            $hasRow[$method->method_group])) {
                        $groupData[$method->method_group][] = [
                            'name' => $rowName[$method->method_row],
                            'sign' => $method->method_row,
                            'methods' => $rowData[$method->method_group][$method->method_row],
                        ];
                        $hasRow[$method->method_group][] = $method->method_row;
                    }
                }

                // 组
                $defaultGroup = '';
                $defaultMethod = '';
                $hasGroup = [];
                foreach ($methods as $index => $method) {
                    if ($index == 0) {
                        $defaultGroup = $method->method_group;
                        $defaultMethod = $method->method_id;
                    }

                    // 组
                    if (!in_array($method->method_group, $hasGroup)) {

                        $methodData[] = [
                            'name' => $groupName[$lottery->series_id][$method->method_group],
                            'sign' => $method->method_group,
                            'rows' => $groupData[$method->method_group]
                        ];

                        $hasGroup[] = $method->method_group;
                    }

                }

                $cacheData[$lottery->en_name] = [
                    'lottery' => $lottery,
                    'methodConfig' => $methodData,
                    'defaultGroup' => $defaultGroup,
                    'defaultMethod' => $defaultMethod,
                ];
                $hourToStore = 24;
                $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
                Cache::put($redisKey, $cacheData, $expiresAt);
            }
        }
        return $this->msgOut(true, $cacheData);
    }

    // 历史奖期

    /**
     * @return JsonResponse
     * @todo  需要改真实数据 暂时先从那边挪接口
     */
    public function issueHistory(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'lottery_sign' => 'required|string|min:4|max:10|exists:lotteries,en_name',
            'count' => 'integer',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $data = [
            [
                'issue_no' => '201809221',
                'code' => '1,2,3,4,5'
            ],
            [
                'issue_no' => '201809222',
                'code' => '1,2,3,4,5'
            ],
            [
                'issue_no' => '201809223',
                'code' => '1,2,3,4,5'
            ],
        ];
        return $this->msgOut(true, $data);
    }

    /**
     * 7. 游戏-可用奖期
     * @return JsonResponse
     */
    public function availableIssues(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'lottery_sign' => 'required|string|min:4|max:10|exists:lotteries,en_name',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $lotterySign = $this->inputs['lottery_sign'];
        $lottery = LotteriesModel::findBySign($lotterySign);
        $canUserInfo = IssueModel::getCanBetIssue($lotterySign, $lottery->max_trace_number);
        $canBetIssueData = [];
        $currentIssue = [];
        foreach ($canUserInfo as $index => $issue) {
            if ($index <= 0) {
                $currentIssue = [
                    'issue_no' => $issue->issue,
                    'begin_time' => $issue->begin_time,
                    'end_time' => $issue->end_time,
                    'open_time' => $issue->allow_encode_time
                ];
            }
            $canBetIssueData[] = [
                'issue_no' => $issue->issue,
                'begin_time' => $issue->begin_time,
                'end_time' => $issue->end_time,
                'open_time' => $issue->allow_encode_time
            ];
        }
        // 上一期
        $_lastIssue = IssueModel::getLastIssue($lotterySign);
        $lastIssue = [
            'issue_no' => $_lastIssue->issue,
            'begin_time' => $_lastIssue->begin_time,
            'end_time' => $_lastIssue->end_time,
            'open_time' => $_lastIssue->allow_encode_time,
            'open_code' => '1,2,3,4,5'
        ];
        $data = [
            'issueInfo' => $canBetIssueData,
            'currentIssue' => $currentIssue,
            'lastIssue' => $lastIssue,
        ];
        return $this->msgOut(true, $data);
    }

    public function projectHistory()
    {
        $validator = Validator::make($this->inputs, [
            'count'=>'required|integer|min:10|max:100',
            'lottery_sign' => 'required|string|min:4|max:10|exists:lotteries,en_name',
            'start' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $lotterySign = $this->inputs['lottery_sign'];
        $start          = $this->inputs['start'];//0
        $count          = $this->inputs['count'];//10
        $data   = Project::getGamePageList($lotterySign, $start, $count);
        return $this->msgOut(true, $data);
    }


}
