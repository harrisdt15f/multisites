<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-27 11:02:52
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-27 15:04:55
 */
namespace App\Http\Controllers\BackendApi\Report;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\AccountChangeReport;
use App\Models\AccountChangeType;
use App\Models\UserRechargeHistory;
use Illuminate\Support\Facades\Validator;

class reportManagementController extends BackEndApiMainController
{
    //玩家帐变报表
    public function userAccountChange()
    {
        $accountChangeEloq = new AccountChangeReport();
        $searchAbleFields = ['username', 'type_sign', 'is_for_agent'];
        $fixedJoin = 1;
        $withTable = 'changeType';
        $withSearchAbleFields = ['in_out', 'type'];
        $field = 'updated_at';
        $type = 'desc';
        $datas = $this->generateSearchQuery($accountChangeEloq, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields, $field, $type);
        foreach ($datas as $key => $report) {
            $data = $report->toArray();
            $reportArr = [
                'username' => $data['username'],
                'amount' => $data['amount'],
                'balance' => $data['balance'],
                'type_name' => $data['type_name'],
                'in_out' => $data['change_type']['in_out'],
                'created_at' => $data['created_at'],
            ];
            $datas[$key] = $reportArr;
        }
        return $this->msgOut(true, $datas);
    }

    //玩家充值报表
    public function userRechargeHistory()
    {
        $RechargeHistoryEloq = new UserRechargeHistory();
        $searchAbleFields = ['user_name', 'company_order_num', 'deposit_mode', 'status'];
        $fixedJoin = 0;
        $field = 'updated_at';
        $type = 'desc';
        $datas = $this->generateSearchQuery($RechargeHistoryEloq, $searchAbleFields, $fixedJoin, null, null, $field, $type);
        return $this->msgOut(true, $datas);
    }
}
