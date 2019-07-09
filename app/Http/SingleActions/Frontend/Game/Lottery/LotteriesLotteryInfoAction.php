<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class LotteriesLotteryInfoAction
{
    /**
     * 游戏 彩种详情
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $lotteries = LotteryList::where('status', 1)->get();
        $cacheData = [];
        $redisKey = 'frontend.lottery.lotteryInfo';
        if (Cache::has($redisKey)) {
            $cacheData = Cache::get($redisKey);
        } else {
            $defaultGroup = '';
            $defaultMethod = '';
            foreach ($lotteries as $lottery) {
                $lottery->valid_modes = $lottery->getFormatMode();
                // 获取所有玩法
                $methods = LotteryMethod::getMethodConfig($lottery->en_name);
                $methodData = [];
                $groupName = config('game.method.group_name');
                $rowName = config('game.method.row_name');
                $groupData = [];
                $hasRow = [];
                foreach ($methods as $index => $methodItem) {
                    // 行
                    if (!isset($groupData[$methodItem->method_group])) {
                        $groupData[$methodItem->method_group] = [];
                    }
                    if (!isset($hasRow[$methodItem->method_group]) || !in_array($methodItem->method_row,
                            $hasRow[$methodItem->method_group])) {
                        $groupData[$methodItem->method_group][] = [
                            'name' => $rowName[$methodItem->method_row],
                            'sign' => $methodItem->method_row,
                            'methods' => $methodItem->getMethodRuleDatas(),// 获取详细玩法规则
                        ];
                        $hasRow[$methodItem->method_group][] = $methodItem->method_row;
                    }
                    //###################
                    // 组
                    $hasGroup = [];
                    if ($index == 0) {
                        $defaultGroup = $methodItem->method_group;
                        $defaultMethod = $methodItem->method_id;
                    }
                    if (!in_array($methodItem->method_group, $hasGroup)) {
                        $methodData[] = [
                            'name' => $groupName[$lottery->series_id][$methodItem->method_group],
                            'sign' => $methodItem->method_group,
                            'rows' => $groupData[$methodItem->method_group],
                        ];
                        $hasGroup[] = $methodItem->method_group;
                    }
                    //##################
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
        return $contll->msgOut(true, $cacheData);
    }
}
