<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\IssueRulesModel;
use App\Models\Game\Lottery\MethodsModel;
use App\Models\Game\Lottery\Logics\LotteryIssueGenerate;
use App\Models\Game\Lottery\Logics\LotteryLogics;

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

    public static $rules = [
        'cn_name'           => 'required|min:4|max:32',
        'en_name'           => 'required|min:4|max:32',
        'series_id'         => 'required|min:2|max:32',
        'max_trace_number'  => 'required|min:1|max:32',
        'issue_format'      => 'required|min:2|max:32',
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
