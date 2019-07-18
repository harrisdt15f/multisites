<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryIssue;

class FrontendLotteryNoticeList extends BaseModel
{
    protected $guarded = ['id'];

    public function oneIssues()
    {
        return $this->hasOne(LotteryIssue::class, 'lottery_id', 'lotteries_id')->select('lottery_id', 'issue', 'official_code', 'encode_time')->where('status_encode', 1)->orderBy('issue', 'desc');
    }
}
