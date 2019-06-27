<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-27 11:21:23
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-27 11:26:31
 */
namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Illuminate\Http\JsonResponse;

class RechargeCheckDetailAction
{
    protected $model;

    /**
     * @param  BackendAdminRechargehumanLog  $backendAdminRechargehumanLog
     */
    public function __construct(BackendAdminRechargehumanLog $backendAdminRechargehumanLog)
    {
        $this->model = $backendAdminRechargehumanLog;
    }

    /**
     * 人工充值列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {

        $fixedJoin = 1;
        $withTable = 'auditFlow';
        $withSearchAbleFields = ['apply_note'];
        $searchAbleFields = ['status', 'type', 'user_name'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $contll->inputs['type'] = 2;
        $data = $contll->generateSearchQuery($this->model, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields, $orderFields, $orderFlow);
        return $contll->msgOut(true, $data);
    }
}
