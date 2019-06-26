<?php

namespace App\Http\Controllers\FrontendApi\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesAvailableIssuesRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesBetRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesIssueHistoryRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesProjectHistoryRequest;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesAvailableIssuesAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesIssueHistoryAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesLotteryInfoAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesLotteryListAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesProjectHistoryAction;
use App\Lib\Locker\AccountLocker;
use App\Lib\Logic\AccountChange;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends FrontendApiMainController
{
    /**
     * 获取彩票列表
     * @param  LotteriesLotteryListAction  $action
     * @return JsonResponse
     */
    public function lotteryList(LotteriesLotteryListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 游戏 彩种详情
     * @param  LotteriesLotteryInfoAction  $action
     * @return JsonResponse
     */
    public function lotteryInfo(LotteriesLotteryInfoAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 历史奖期
     * @param  LotteriesIssueHistoryRequest  $request
     * @param  LotteriesIssueHistoryAction  $action
     * @return JsonResponse
     * @todo  需要改真实数据 暂时先从那边挪接口
     */
    public function issueHistory(
        LotteriesIssueHistoryRequest $request,
        LotteriesIssueHistoryAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 7. 游戏-可用奖期
     * @param  LotteriesAvailableIssuesRequest  $request
     * @param  LotteriesAvailableIssuesAction  $action
     * @return JsonResponse
     */
    public function availableIssues(
        LotteriesAvailableIssuesRequest $request,
        LotteriesAvailableIssuesAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 游戏-下注历史
     * @param  LotteriesProjectHistoryRequest  $request
     * @param  LotteriesProjectHistoryAction  $action
     * @return JsonResponse
     */
    public function projectHistory(
        LotteriesProjectHistoryRequest $request,
        LotteriesProjectHistoryAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
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
                    $position = (array)$item['position'];
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
                return $this->msgOut(false, [], '', "对不起, 模式{$mode}, 不存在!");
            }
            // 奖金组 - 游戏
            $prizeGroup = (int)$item['prize_group'];
            if (!$lottery->isValidPrizeGroup($prizeGroup)) {
                return $this->msgOut(false, [], '', "对不起, 奖金组{$prizeGroup}, 游戏未开放!");
            }
            // 奖金组 - 用户
            if ($usr->prize_group < $prizeGroup) {
                return $this->msgOut(false, [], '', "对不起, 奖金组{$prizeGroup}, 用户不合法!");
            }
            // 投注号码
            $ball = $item['codes'];
            if (!$oMethod->regexp($ball)) {
                return $this->msgOut(false, [], '', "对不起, 玩法{$methodId}, 注单号码不合法!");
            }
            // 倍数
            $times = (int)$item['times'];
            if (!$lottery->isValidTimes($times)) {
                return $this->msgOut(false, [], '', "对不起, 倍数{$times}, 不合法!");
            }
            $price = (int)$item['price'];
            $priceConfig = config('game.main.price', [1, 2]);
            if (!$price || !in_array($price, $priceConfig)) {
                return $this->msgOut(false, [], '', "对不起, 单价{$price}, 不合法!");
            }

            // 单价花费
            $singleCost = $mode * $times * $price * $item['count'];
            if ($singleCost !== $item['cost']) {
                return $this->msgOut(false, [], '', '对不起, 总价计算错误!');
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
        $traceData = array_keys($inputDatas['trace_issues']);
        // 检测追号奖期
        if (!$traceData || !is_array($traceData)) {
            return $this->msgOut(false, [], '', '对不起, 无效的追号奖期数据!');
        }
        $traceDataCollection = $lottery->checkTraceData($traceData);
        if (count($traceData) !== $traceDataCollection->count()) {
            return $this->msgOut(false, [], '', '对不起, 追号奖期不正确!');
        }
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
            $traceData = count($inputDatas['trace_issues']) > 1 ? array_slice($inputDatas['trace_issues'], 1) : [];
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
                    return $this->msgOut(false, [], '', '对不起, '.$res);
                }
            }
            $accountChange->triggerSave();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $accountLocker->release();
            Log::info('投注-异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine()); //Clog::userBet
            return $this->msgOut(false, [], '', '对不起, '.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
        }
        $accountLocker->release();
        return $this->msgOut(true, $data);
    }

    public function setWinPrize()
    {
        LotteryIssue::calculateEncodedNumber('cqssc', '190624052');
    }
}
