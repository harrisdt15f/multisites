<?php

namespace App\Models\Game\Lottery\Logics;

trait LotteryTraceListLogics
{
    public function getUnfinishedTrace($traceId, $userId)
    {
        return $this::where([['trace_id', $traceId], ['status', self::STATUS_WAITING], ['user_id', $userId]])->get();
    }
}
