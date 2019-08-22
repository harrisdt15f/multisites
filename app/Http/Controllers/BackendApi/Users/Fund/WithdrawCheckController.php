<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\RechargeCheckAuditFailureRequest;
use App\Http\Requests\Backend\Users\Fund\RechargeCheckAuditSuccessRequest;
use App\Http\SingleActions\Backend\Users\Fund\RechargeCheckAuditFailureAction;
use App\Http\SingleActions\Backend\Users\Fund\RechargeCheckAuditSuccessAction;
use App\Http\SingleActions\Backend\Users\Fund\RechargeCheckDetailAction;
use App\Http\SingleActions\Payment\PayWithdrawAction;
use Illuminate\Http\JsonResponse;

class WithdrawCheckController extends BackEndApiMainController
{
    /**
     * 提现详情
     * @param PayWithdrawAction $action
     * @return JsonResponse
     */
    public function detail(PayWithdrawAction $action): JsonResponse
    {
        return $action->detail($this);
    }

    /**
     * 审核通过
     * @param  PayWithdrawAction  $action
     * @return JsonResponse
     */
    public function auditSuccess(PayWithdrawAction $action): JsonResponse
    {
        return $action->auditSuccess($this);
    }

    /**
     * 审核驳回
     * @param PayWithdrawAction $action
     * @return JsonResponse
     */
    public function auditFailure(PayWithdrawAction $action): JsonResponse
    {
        return $action->auditFailure($this);
    }
}
