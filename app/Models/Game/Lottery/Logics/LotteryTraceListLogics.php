<?php

namespace App\Models\Game\Lottery\Logics;

trait LotteryTraceListLogics
{
    public function getUnfinishedTrace($traceId, $userId)
    {
        return $this::where([['trace_id', $traceId], ['status', 0], ['user_id', $userId]])->get();
    }
}
