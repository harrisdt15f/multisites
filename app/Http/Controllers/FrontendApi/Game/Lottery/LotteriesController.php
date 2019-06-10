<?php

namespace App\Http\Controllers\FrontendApi\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesBetRequest;
use App\Lib\Locker\AccountLocker;
use App\Lib\Logic\AccountChange;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\LotteryMethod;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends FrontendApiMainController
{
    public function lotteryList(): JsonResponse
    {
        $lotteries = LotteryList::with(['issueRule:lottery_id,begin_time,end_time'])->get([
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
                    'list' => [],
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
        $lotteries = LotteryList::where('status', 1)->get();
        $cacheData = [];
        $redisKey = 'frontend.lottery.lotteryInfo';
        if (Cache::has($redisKey)) {
            $cacheData = Cache::get($redisKey);
        } else {
            foreach ($lotteries as $lottery) {
                $lottery->valid_modes = $lottery->getFormatMode();
                // 获取所有玩法
                $methods = LotteryMethod::getMethodConfig($lottery->en_name);
                $methodData = [];

                $groupName = config('game.method.group_name');
                $rowName = config('game.method.row_name');

                $rowData = [];
                foreach ($methods as $index => $method) {
                    $rowData[$method->method_group][$method->method_row][] = [
                        'method_name' => $method->method_name,
                        'method_id' => $method->method_id,
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
                            'rows' => $groupData[$method->method_group],
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
                $expiresAt = Carbon::now()->addHours($hourToStore);
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
            'lottery_sign' => 'required|string|min:4|max:10|exists:lottery_lists,en_name',
            'count' => 'integer',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $data = [
            [
                'issue_no' => '201809221',
                'code' => '1,2,3,4,5',
            ],
            [
                'issue_no' => '201809222',
                'code' => '1,2,3,4,5',
            ],
            [
                'issue_no' => '201809223',
                'code' => '1,2,3,4,5',
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
            'lottery_sign' => 'required|string|min:4|max:10|exists:lottery_lists,en_name', //lottery_lists
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $lotterySign = $this->inputs['lottery_sign'];
        $lottery = LotteryList::findBySign($lotterySign);
        $canUserInfo = LotteryIssue::getCanBetIssue($lotterySign, $lottery->max_trace_number);
        $canBetIssueData = [];
        $currentIssue = [];
        foreach ($canUserInfo as $index => $issue) {
            if ($index <= 0) {
                $currentIssue = [
                    'issue_no' => $issue->issue,
                    'begin_time' => $issue->begin_time,
                    'end_time' => $issue->end_time,
                    'open_time' => $issue->allow_encode_time,
                ];
            }
            $canBetIssueData[] = [
                'issue_no' => $issue->issue,
                'begin_time' => $issue->begin_time,
                'end_time' => $issue->end_time,
                'open_time' => $issue->allow_encode_time,
            ];
        }
        // 上一期
        $_lastIssue = LotteryIssue::getLastIssue($lotterySign);
        $lastIssue = [
            'issue_no' => $_lastIssue->issue,
            'begin_time' => $_lastIssue->begin_time,
            'end_time' => $_lastIssue->end_time,
            'open_time' => $_lastIssue->allow_encode_time,
            'open_code' => '1,2,3,4,5',
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
            'count' => 'required|integer|min:10|max:100',
            'lottery_sign' => 'required|string|min:4|max:10|exists:lottery_lists,en_name',
            'start' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $lotterySign = $this->inputs['lottery_sign'];
        $start = $this->inputs['start']; //0
        $count = $this->inputs['count']; //10
        $data = Project::getGamePageList($lotterySign, $start, $count);
        return $this->msgOut(true, $data);
    }

    public function bet(LotteriesBetRequest $request)
    {
        $inputDatas = $request->validated();
        $usr = $this->currentAuth->user();
        $lotterySign = $inputDatas['lottery_sign'];
        $lottery = LotteryList::getLottery($lotterySign);
        $betDetail = [];
        $_totalCost = 0;
        // 初次解析
        $_balls = [];
        foreach ($inputDatas['balls'] as $item) {
            $methodId = $item['method_id'];
            $method = $lottery->getMethod($methodId);
            $validator = Validator::make($method, [
                'status' => 'required|in:1', //玩法状态
                'object' => 'required', //玩法对象
                'method_name' => 'required', // 玩法未定义
            ]);
            if ($validator->fails()) {
                return $this->msgOut(false, [], '400', $validator->errors()->first());
            }
            $oMethod = $method['object']; // 玩法 - 对象
            // 转换格式
            $project['codes'] = $oMethod->resolve($oMethod->parse64($item['codes']));

            if ($oMethod->supportExpand) {
                $position = [];
                if (isset($item['position'])) {
                    $position = (array) $item['position'];
                }
                if (!$oMethod->checkPos($position)) {
                    return "对不起, 玩法{$method['name']}位置不正确!";
                }
                $expands = $oMethod->expand($item['codes'], $position);
                foreach ($expands as $expand) {
                    $item['method_id'] = $expand['method_id'];
                    $item['codes'] = $expand['codes'];
                    $item['count'] = $expand['count'];
                    $item['cost'] = $item['mode'] * $item['times'] * $item['price'];
                    $_balls[] = $item;
                }
            } else {
                $_balls[] = $item;
            }
        }
        $inputDatas['balls'] = $_balls;
        foreach ($inputDatas['balls'] as $item) {
            $methodId = $item['method_id'];
            $method = $lottery->getMethod($methodId);
            $oMethod = $method['object'];
            // 模式
            $mode = $item['mode'];
            $modes = config('game.main.modes_array');
            if (!in_array($mode, $modes)) {
                return "对不起, 模式{$mode}, 不存在!";
            }
            // 奖金组 - 游戏
            $prizeGroup = intval($item['prize_group']);
            if (!$lottery->isValidPrizeGroup($prizeGroup)) {
                return "对不起, 奖金组{$prizeGroup}, 游戏未开放!";
            }
            // 奖金组 - 用户
            if ($usr->prize_group < $prizeGroup) {
                return "对不起, 奖金组{$prizeGroup}, 用户不合法!";
            }
            // 投注号码
            $ball = $item['codes'];
            if (!$oMethod->regexp($ball)) {
                return "对不起, 玩法{$methodId}, 注单号码不合法!";
            }
            // 倍数
            $times = intval($item['times']);
            if (!$lottery->isValidTimes($times)) {
                return "对不起, 倍数{$times}, 不合法!";
            }
            $price = intval($item['price']);
            $priceConfig = config('game.main.price', [1, 2]);
            if (!$price || !in_array($price, $priceConfig)) {
                return "对不起, 单价{$price}, 不合法!";
            }

            // 单价花费
            $singleCost = $mode * $times * $price * $item['count'];
            if ($singleCost != $item['cost']) {
                return '对不起, 总价计算错误!';
            }
            $_totalCost += $singleCost;
            $betDetail[] = [
                'method_id' => $methodId,
                'method_name' => $method['method_name'],
                'mode' => $mode,
                'prize_group' => $prizeGroup,
                'times' => $times,
                'price' => $price,
                'total_price' => $singleCost,
                'code' => $ball,
            ];
        }
        // 投注期号
        $traceData = $inputDatas['trace_issues'];
        // 检测追号奖期
        if (!$traceData || !is_array($traceData)) {
            return '对不起, 无效的追号奖期数据!';
        }
        $traceDataCollection = $lottery->checkTraceData($traceData);
        if (count($traceData) !== $traceDataCollection->count()) {
            return '对不起, 追号奖期不正确!';
        }
        $traceData = $traceDataCollection->pluck('issue');
        // 获取当前奖期
        $currentIssue = LotteryIssue::getCurrentIssue($lottery->en_name);
        if (!$currentIssue) {
            return $this->msgOut(false, [], '', '对不起, 奖期已过期!');
        }
        // 奖期和追号
        /*if ($currentIssue->issue != $traceData[0]) {
        return $this->msgOut(false, [], '', '对不起, 奖期已过期!');
        }*/
        $accountLocker = new AccountLocker($usr->id);
        if (!$accountLocker->getLock()) {
            return $this->msgOut(false, [], '', '对不起, 获取账户锁失败!');
        }
        $account = $usr->account()->first();
        if ($account->balance < $_totalCost * 10000) {
            $accountLocker->release();
            return $this->msgOut(false, [], '', '对不起, 当前余额不足!');
        }
        DB::beginTransaction();
        try {
            $traceData = count($traceData) > 1 ? array_slice($traceData, 1) : [];
            $from = $inputDatas['from'] ?? 1;
            $data = Project::addProject($usr, $lottery, $currentIssue, $betDetail, $traceData, $from);
            // 帐变
            $accountChange = new AccountChange();
            $accountChange->setReportMode(AccountChange::MODE_REPORT_AFTER);
            $accountChange->setChangeMode(AccountChange::MODE_CHANGE_AFTER);
            foreach ($data['project'] as $item) {
                $params = [
                    'user_id' => $usr->id,
                    'amount' => $item['cost'] * 10000,
                    'lottery_id' => $item['lottery_id'],
                    'method_id' => $item['method_id'],
                    'project_id' => $item['id'],
                    'issue' => $currentIssue->issue,
                ];
                $res = $accountChange->doChange($account, 'bet_cost', $params);
                if ($res !== true) {
                    DB::rollBack();
                    $accountLocker->release();
                    return $this->msgOut(false, [], '', '对不起, ' . $res);
                }
            }
            $accountChange->triggerSave();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $accountLocker->release();
            Log::info('投注-异常:' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine()); //Clog::userBet
            return $this->msgOut(false, [], '', '对不起, ' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine());
        }
        $accountLocker->release();
        return $this->msgOut(true, $data);
    }
}
