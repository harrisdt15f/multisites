<?php

namespace App\Models\User\Fund;

use App\Models\BaseModel;
use App\Models\User\Fund\Logics\FrontendUsersAccountsReportLogics;

class FrontendUsersAccountsReport extends BaseModel
{
    use FrontendUsersAccountsReportLogics;

    protected $guarded = ['id'];

    public function changeType()
    {
        $data = $this->hasOne(FrontendUsersAccountsType::class, 'sign', 'type_sign')->select('sign', 'in_out');
        return $data;
    }
}
