<?php

namespace App\Models\Game\Lottery\Logics;

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
    public function getUnfinishedTraceAllWating($traceId, $userId, $all = false)
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
}
