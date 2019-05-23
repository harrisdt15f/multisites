<?php

namespace App\Models;

use App\Models\Game\lottery\Traits\LotteryIssueGenerate;
use App\Models\Game\lottery\Traits\LotteryLogics;

class LotteriesModel extends BaseModel
{
    use LotteryIssueGenerate, LotteryLogics;
    protected $table = 'lotteries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cn_name',
        'en_name',
        'series_id',
        'is_fast',
        'auto_open',
        'max_trace_number',
        'day_issue',
        'issue_format',
        'issue_type',
        'valid_code',
        'code_length',
        'positions',
        'min_prize_group',
        'max_prize_group',
        'min_times',
        'max_times',
        'valid_modes',
        'status',
    ];

    public function issueRule()
    {
        return $this->hasOne(IssueRulesModel::class, 'lottery_id', 'en_name');
    }

    public function gameMethods()
    {
        return $this->hasOne(MethodsModel::class, 'lottery_id', 'en_name')->where('status', 1);
    }
}
