<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\RechargeCheckAuditFailureRequest;
use App\Http\Requests\Backend\Users\Fund\RechargeCheckAuditSuccessRequest;
use App\Lib\Common\AccountChange;
use App\Lib\Common\FundOperationRecharge;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\Message\BackendSystemNoticeList;
use App\Models\BackendAdminAuditFlowList;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\AccountChangeReport;
use App\Models\User\Fund\AccountChangeType;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersRechargeHistorie;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RechargeCheckController extends BackEndApiMainController
{
    protected $eloqM = 'User\Fund\BackendAdminRechargehumanLog';
    protected $successMessage = '你的人工充值申请已通过';
    protected $failureMessage = '你的人工充值申请被驳回';

    /**
     * 人工充值列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $fixedJoin = 1;
        $withTable = 'auditFlow';
        $withSearchAbleFields = ['apply_note'];
        $searchAbleFields = ['status', 'type', 'user_name'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $this->inputs['type'] = 2;
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields, $orderFields, $orderFlow);
        return $this->msgOut(true, $data);
    }

    /**
     * 审核通过
     * @param  RechargeCheckAuditSuccessRequest $request
     * @return JsonResponse
     */
    public function auditSuccess(RechargeCheckAuditSuccessRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        // 审核表
        $rechargeLog = $this->eloqM::find($inputDatas['id']);
        if ($rechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        $auditFlow = BackendAdminAuditFlowList::where('id', $rechargeLog->audit_flow_id)->first();
        if (is_null($auditFlow)) {
            return $this->msgOut(false, [], '100904');
        }
        //检查是否存在 人工充值 的帐变类型表
        $accountChangeTypeEloq = AccountChangeType::where('sign', 'artificial_recharge')->first();
        if (is_null($accountChangeTypeEloq)) {
            return $this->msgOut(false, [], '100901');
        }
        DB::beginTransaction();
        try {
            // 修改 backend_admin_rechargehuman_logs 表 的审核状态
            $rechargeLogEdit = ['status' => $rechargeLog::AUDITSUCCESS];
            $rechargeLog->fill($rechargeLogEdit);
            $rechargeLog->save();
            // 修改 users_recharge_histories 表 的审核状态
            $historyEloq = UsersRechargeHistorie::where('audit_flow_id', $rechargeLog->audit_flow_id)->first();
            $historyEdit = ['status' => $historyEloq::AUDITSUCCESS];
            $historyEloq->fill($historyEdit);
            $historyEloq->save();
            //修改backend_admin_audit_flow_lists审核表
            $userData = FrontendUser::where('id', $rechargeLog->user_id)->with('account')->first();
            $balance = $userData->account->balance + $rechargeLog->amount;
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $inputDatas['auditor_note']);
            //修改用户金额
            $UserAccounts = FrontendUsersAccount::where('user_id', $rechargeLog->user_id)->first();
            $UserAccountsEdit = ['balance' => $balance];
            $editStatus = FrontendUsersAccount::where('user_id', $UserAccounts->user_id)->where('updated_at', $UserAccounts->updated_at)->update($UserAccountsEdit);
            if ($editStatus === 0) {
                DB::rollBack();
                return $this->msgOut(false, [], '100902');
            }
            //用户帐变表
            $accountChangeReportEloq = new AccountChangeReport();
            $accountChangeClass = new AccountChange();
            $accountChangeClass->addData($accountChangeReportEloq, $userData, $rechargeLog['amount'], $UserAccounts->balance, $balance, $accountChangeTypeEloq);
            //发送站内消息提醒管理员
            $this->sendMessage($rechargeLog->admin_id, $this->successMessage);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 审核驳回
     * @param  RechargeCheckAuditFailureRequest $request
     * @return JsonResponse
     */
    public function auditFailure(RechargeCheckAuditFailureRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $rechargeLog = $this->eloqM::find($inputDatas['id']);
        if ($rechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        $adminFundData = BackendAdminRechargePocessAmount::where('admin_id', $rechargeLog->admin_id)->first();
        if (is_null($adminFundData)) {
            return $this->msgOut(false, [], '100903');
        }
        $newFund = $adminFundData->fund + $rechargeLog->amount;
        DB::beginTransaction();
        try {
            // 修改 backend_admin_rechargehuman_logs 表 的审核状态
            $rechargeLogEdit = ['status' => $rechargeLog::AUDITFAILURE];
            $rechargeLog->fill($rechargeLogEdit);
            $rechargeLog->save();
            // 修改 users_recharge_histories 表 的审核状态
            $historyEloq = UsersRechargeHistorie::where('audit_flow_id', $rechargeLog->audit_flow_id)->first();
            $historyEdit = ['status' => $historyEloq::AUDITFAILURE];
            $historyEloq->fill($historyEdit);
            $historyEloq->save();
            //退还管理员人工充值额度
            $auditFlow = BackendAdminAuditFlowList::where('id', $rechargeLog->audit_flow_id)->first();
            $adminFundDataEdit = ['fund' => $newFund];
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $inputDatas['auditor_note']);
            $adminFundData->fill($adminFundDataEdit);
            $adminFundData->save();
            //返还额度后  backend_admin_rechargehuman_logs 记录表
            $rechargeLogeloqM = new $this->eloqM;
            $type = $rechargeLogeloqM::SYSTEM;
            $in_out = $rechargeLogeloqM::INCREMENT;
            $comment = '[充值审核失败额度返还]==>+' . $rechargeLog['amount'] . '|[目前额度]==>' . $newFund;
            $fundOperationClass = new FundOperationRecharge();
            $fundOperationClass->insertOperationDatas($rechargeLogeloqM, $type, $in_out, null, null, $auditFlow->admin_id, $auditFlow->admin_name, $rechargeLog->amount, $comment, null);
            //发送站内消息提醒管理员
            $this->sendMessage($rechargeLog->admin_id, $this->failureMessage);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 审核时 修改审核表
     * @param  object $eloq         [审核表Eloq]
     * @param  object $partnerAdmin [adminEloq]
     * @param  string $auditor_note [备注]
     * @return void
     */
    public function auditFlowEdit($eloq, $partnerAdmin, $auditor_note): void
    {
        $editData = [
            'auditor_id' => $partnerAdmin->id,
            'auditor_name' => $partnerAdmin->name,
            'auditor_note' => $auditor_note,
        ];
        $eloq->fill($editData);
        $eloq->save();
    }

    /**
     * 审核后发送站内消息提醒申请人
     * @param  int     $adminId 申请人id
     * @param  string  $message 消息内容
     * @return void
     */
    public function sendMessage($adminId, $message): void
    {
        $messageObj = new InternalNoticeMessage();
        $type = BackendSystemNoticeList::AUDIT;
        $admin = BackendAdminUser::select('id', 'group_id')->find($adminId);
        if ($admin !== null) {
            $messageObj->insertMessage($type, $message, $admin->toArray());
        }
    }
}
