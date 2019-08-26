<?php

namespace App\Models\User\Fund;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryMethod;
use App\Models\User\Fund\Logics\FrontendUsersAccountsReportLogics;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Game\Lottery\LotteryList;

class FrontendUsersAccountsReport extends BaseModel
{
    use FrontendUsersAccountsReportLogics;

    protected $guarded = ['id'];

    public function changeType()
    {
        $data = $this->hasOne(FrontendUsersAccountsType::class, 'sign', 'type_sign')->select('sign', 'in_out');
        return $data;
    }

    /**
     * @return HasOne
     */
    public function gameMethods(): HasOne
    {
        return $this->hasOne(LotteryMethod::class, 'method_id', 'method_id');
    }

    /**
     * @return HasOne
     */
    public function lottery(): HasOne
    {
        return $this->hasOne(LotteryList::class, 'en_name', 'lottery_id');
    }
}
