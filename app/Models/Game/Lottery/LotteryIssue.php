<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\IssueLogics;

class LotteryIssue extends BaseModel
{
    use IssueLogics;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lottery_id',
        'lottery_name',
        'issue',
        'issue_rule_id',
        'begin_time',
        'end_time',
        'official_open_time',
        'allow_encode_time',
        'official_code',
        'status_encode',
        'status_calculated',
        'status_prize',
        'status_commission',
        'status_trace',
        'encode_time',
        'calculated_time',
        'prize_time',
        'commission_time',
        'trace_time',
        'encode_id',
        'encode_username',
        'day',
    ];
}
