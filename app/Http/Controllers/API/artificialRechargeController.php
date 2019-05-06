<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class artificialRechargeController extends ApiMainController
{
    protected $eloqM = 'UserHandleModel';
    public function users()
    {
        $fixedJoin = 1;
        $withTable = 'account';
        $searchAbleFields = ['username', 'type', 'vip_level', 'is_tester', 'frozen_type', 'prize_group', 'level_deep', 'register_ip'];
        $withSearchAbleFields = ['balance'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $this->msgout(true, $data);
    }
    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'fund' => 'required|numeric',
        ]);
    }

}
