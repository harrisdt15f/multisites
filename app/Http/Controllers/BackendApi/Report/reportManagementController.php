<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-27 11:02:52
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 18:19:50
 */
namespace App\Http\Controllers\BackendApi\Report;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\SingleActions\Backend\Report\reportManagementAccountChangeTypeAction;
use App\Http\SingleActions\Backend\Report\reportManagementUserAccountChangeAction;
use App\Http\SingleActions\Backend\Report\reportManagementUserBetsAction;
use App\Http\SingleActions\Backend\Report\reportManagementUserRechargeHistoryAction;
use Illuminate\Http\JsonResponse;

class reportManagementController extends BackEndApiMainController
{
    /**
     * 玩家帐变报表
     * @param   reportManagementUserAccountChangeAction $action
     * @return  JsonResponse
     */
    public function userAccountChange(reportManagementUserAccountChangeAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 玩家充值报表
     * @param   reportManagementUserRechargeHistoryAction $action
     * @return  JsonResponse
     */
    public function userRechargeHistory(reportManagementUserRechargeHistoryAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 帐变类型列表
     * @param   reportManagementAccountChangeTypeAction $action
     * @return  JsonResponse
     */
    public function accountChangeType(reportManagementAccountChangeTypeAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 玩家注单报表
     * @param   reportManagementUserBetsAction $action
     * @return  JsonResponse
     */
    public function userBets(reportManagementUserBetsAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
