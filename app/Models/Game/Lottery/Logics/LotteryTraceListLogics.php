<?php

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryList;
use App\Models\Project;
use App\Models\User\FrontendUser;
use Illuminate\Support\Facades\Request;

trait LotteryTraceListLogics
{
    //获取所有等待开奖的注单信息
    public function getUnfinishedTrace($traceId, $userId)
    {
        return self::where([['trace_id', $traceId], ['status', self::STATUS_WAITING], ['user_id', $userId]])->get();
    }

    //获取已经结束注单
    public function getFinishedTrace($traceId, $userId)
    {
        return self::where([
            ['trace_id', $traceId],
            ['status', self::STATUS_FINISHED],
            ['user_id', $userId],
        ])->orwhere([['trace_id', $traceId], ['status', self::STATUS_RUNNING], ['user_id', $userId]])->get();
    }

    //获取正在运行的注单 状态为STATUS_RUNNING[1]的注单
    public function getFinishedTraceToRun($traceId, $userId)
    {
        return self::where([
            ['trace_id', $traceId],
            ['status', self::STATUS_RUNNING],
            ['user_id', $userId],
        ])->get();
    }

    //根据取消的注单号找到lottery_traceID号，再根据此ID找到旗下正在等待的注单信息
    public function getUnfinishedTraceAllWating($traceId, $userId)
    {
        $tracListData = self::where([
            ['id', $traceId],
            ['user_id', $userId],
        ])->first();
        $data = collect([]);
        if ($tracListData !== null) {
            $data = self::where([
                ['trace_id', $tracListData->trace_id],
                ['status', self::STATUS_WAITING],
                ['user_id', $userId],
            ])->get();
        }
        return $data;
    }

    //根据取消的注单号找到lottery_traceID号，再根据此ID找到旗下所有的注单信息
    public function getUnfinishedTraceAll($traceId, $userId)
    {
        $tracListData = self::where([
            ['id', $traceId],
            ['user_id', $userId],
        ])->first();

        $data = collect([]);
        if ($tracListData !== null) {
            $data = self::where([
                ['trace_id', $tracListData->trace_id],
                ['id', '!=', $traceId],
                ['user_id', $userId],
            ])->get();
        }
        return $data;
    }

    //根据取消的注单号找到lottery_traceID号，再根据此ID找到旗下状态正在运行的注单信息
    public function getRuningTrace($traceId, $userId)
    {
        $tracListData = self::where([
            ['id', $traceId],
            ['user_id', $userId],
        ])->first();

        $data = collect([]);
        if ($tracListData !== null) {
            $data = self::where([
                ['trace_id', $tracListData->trace_id],
                ['status', self::STATUS_RUNNING],
                ['id', '!=', $traceId],
                ['user_id', $userId],
            ])->get();
        }
        return $data;
    }

    /**
     * @param $traceId
     * @param $traceData
     * @param $data
     * @param  Project  $project
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param $_item
     * @param $aPrizeSettingOfWay
     * @param $from
     */
    public static function createTraceListData(
        $traceId,
        $traceData,
        $data,
        Project $project,
        FrontendUser $user,
        LotteryList $lottery,
        $_item,
        $aPrizeSettingOfWay,
        $from
    ): void {
        $i = 1;
        foreach ($traceData as $issue => $multiple) {
            if ($i === 1) {
                $project_serial_number = $project->serial_number;
                $status = self::STATUS_RUNNING;
            } else {
                $project_serial_number = null;
                $status = self::STATUS_WAITING;
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
                self::create($traceListData);
            }
            $i++;
        }
    }
}
