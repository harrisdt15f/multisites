<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-27 11:02:52
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-18 15:40:12
 */
namespace App\Http\Controllers\BackendApi\Report;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\AccountChangeReport;
use App\Models\User\Fund\AccountChangeType;
use App\Models\User\UsersRechargeHistorie;
use Illuminate\Http\JsonResponse;

class reportManagementController extends BackEndApiMainController
{
    /**
     * 玩家帐变报表
     * @return JsonResponse
     */
    public function userAccountChange(): JsonResponse
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
                'type_sign' => $data['type_sign'],
                'in_out' => $data['change_type']['in_out'],
                'created_at' => $data['created_at'],
            ];
            $datas[$key] = $reportArr;
        }
        return $this->msgOut(true, $datas);
    }

    /**
     * 玩家充值报表
     * @return JsonResponse
     */
    public function userRechargeHistory(): JsonResponse
    {
        $rechargeHistoryEloq = new UsersRechargeHistorie();
        $searchAbleFields = ['user_name', 'company_order_num', 'deposit_mode', 'status'];
        $field = 'updated_at';
        $type = 'desc';
        $datas = $this->generateSearchQuery($rechargeHistoryEloq, $searchAbleFields, 0, null, null, $field, $type);
        return $this->msgOut(true, $datas);
    }

    /**
     * @return JsonResponse
     */
    public function accountChangeType(): JsonResponse
    {
        $datas = AccountChangeType::select('name', 'sign')->get()->toArray();
        return $this->msgOut(true, $datas);
    }
}
