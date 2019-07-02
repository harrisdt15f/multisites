<?php

namespace App\Models\User\Fund;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryMethod;
use App\Models\User\Fund\Logics\FrontendUsersAccountsReportLogics;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FrontendUsersAccountsReport extends BaseModel
{
    use FrontendUsersAccountsReportLogics;

    protected $fillable = [
        'sign', 'user_id', 'top_id', 'parent_id', 'rid', 'username', 'from_id', 'from_admin_id', 'to_id', 'type_sign', 'type_name', 'lottery_id', 'method_id', 'project_id', 'issue', 'day', 'activity_sign', 'amount', 'before_balance', 'balance', 'before_frozen_balance', 'frozen_balance', 'frozen_type', 'is_tester', 'process_time', 'desc', 'created_at', 'updated_at',
    ];

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
}
