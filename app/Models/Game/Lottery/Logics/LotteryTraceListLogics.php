<?php

namespace App\Models\Game\Lottery\Logics;

trait LotteryTraceListLogics
{
    public function getUnfinishedTrace($traceId, $userId)
    {
        return $this::where([['trace_id', $traceId], ['status', self::STATUS_WAITING], ['user_id', $userId]])->get();
    }

    public function getFinishedTrace($traceId, $userId)
    {
        return $this::where([['trace_id', $traceId], ['status', self::STATUS_FINISHED], ['user_id', $userId]])->orwhere([['trace_id', $traceId], ['status', self::STATUS_RUNNING], ['user_id', $userId]])->get();
    }

    public function getFinishedTraceToRun($traceId, $userId)
    {
        return $this::where([['trace_id', $traceId], ['status', self::STATUS_RUNNING], ['user_id', $userId]])->get();
    }


    public function getUnfinishedTraceAllWating($traceId, $userId, $all = false)
    {
        $tracListData = $this::where([['id', $traceId], ['user_id', $userId]])->first();

        return $this::where([['trace_id', $tracListData->trace_id], ['status', self::STATUS_WAITING], ['user_id', $userId]])->get();
    }


    public function getUnfinishedTraceAll($traceId, $userId)
    {
        $tracListData = $this::where([['id', $traceId], ['user_id', $userId]])->first();

        return $this::where([['trace_id', $tracListData->trace_id], ['id', '!=', $traceId], ['user_id', $userId]])->get();
    }

    public function getRuningTrace($traceId, $userId)
    {
        $tracListData = $this::where([['id', $traceId], ['user_id', $userId]])->first();

        return $this::where([['trace_id', $tracListData->trace_id], ['status', self::STATUS_RUNNING], ['id', '!=', $traceId], ['user_id', $userId]])->get();
    }
}
