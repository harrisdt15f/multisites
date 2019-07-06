<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 10:26:38
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 10:30:42
 */
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
                        'method_group' => $method->method_group,
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
        return $contll->msgOut(true, $cacheData);
    }
}
