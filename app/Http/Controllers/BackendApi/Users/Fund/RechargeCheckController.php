<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\AccountChange;
use App\Lib\Common\FundOperationRecharge;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\Fund\FundOperation;
use App\Models\Admin\Message\NoticeMessage;
use App\Models\Admin\PartnerAdminUsers;
use App\Models\AuditFlow;
use App\Models\User\Fund\AccountChangeReport;
use App\Models\User\Fund\AccountChangeType;
use App\Models\User\Fund\HandleUserAccounts;
use App\Models\User\UserHandleModel;
use App\Models\User\UserRechargeHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RechargeCheckController extends BackEndApiMainController
{
    protected $eloqM = 'User\Fund\ArtificialRechargeLog';
    protected $successMessage = '你的人工充值申请已通过';
    protected $failureMessage = '你的人工充值申请被驳回';

    public function detail()
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

    public function auditSuccess()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'auditor_note' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        // 审核表
        $rechargeLog = $this->eloqM::find($this->inputs['id']);
        $auditFlow = auditFlow::where('id', $rechargeLog->audit_flow_id)->first();
        if ($rechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        //检查是否存在 人工充值 的帐变类型表
        $accountChangeTypeEloq = AccountChangeType::where('sign', 'artificial_recharge')->first();
        if (is_null($accountChangeTypeEloq)) {
            return $this->msgOut(false, [], '100901');
        }
        DB::beginTransaction();
        try {
            // 修改 artificial_recharge_log 表 的审核状态
            $rechargeLogEdit = ['status' => $rechargeLog::AUDITSUCCESS];
            $rechargeLog->fill($rechargeLogEdit);
            $rechargeLog->save();
            // 修改 user_recharge_history 表 的审核状态
            $historyEloq = UserRechargeHistory::where('audit_flow_id', $rechargeLog->audit_flow_id)->first();
            $historyEdit = ['status' => $historyEloq::AUDITSUCCESS];
            $historyEloq->fill($historyEdit);
            $historyEloq->save();
            //修改audit_flow审核表
            $userData = UserHandleModel::where('id', $rechargeLog->user_id)->with('account')->first();
            $balance = $userData->account->balance + $rechargeLog->amount;
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $this->inputs['auditor_note']);
            //修改用户金额
            $UserAccounts = HandleUserAccounts::where('user_id', $rechargeLog->user_id)->first();
            $UserAccountsEdit = ['balance' => $balance];
            $editStatus = HandleUserAccounts::where(function ($query) use ($UserAccounts) {
                $query->where('user_id', $UserAccounts->user_id)
                    ->where('updated_at', $UserAccounts->updated_at);
            })->update($UserAccountsEdit);
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

    public function auditFailure()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'auditor_note' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $rechargeLog = $this->eloqM::find($this->inputs['id']);
        if ($rechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        $adminFundData = FundOperation::where('admin_id', $rechargeLog->admin_id)->first();
        if (is_null($adminFundData)) {
            return $this->msgOut(false, [], '100903');
        }
        $newFund = $adminFundData->fund + $rechargeLog->amount;
        DB::beginTransaction();
        try {
            // 修改 artificial_recharge_log 表 的审核状态
            $rechargeLogEdit = ['status' => $rechargeLog::AUDITFAILURE];
            $rechargeLog->fill($rechargeLogEdit);
            $rechargeLog->save();
            // 修改 user_recharge_history 表 的审核状态
            $historyEloq = UserRechargeHistory::where('audit_flow_id', $rechargeLog->audit_flow_id)->first();
            $historyEdit = ['status' => $historyEloq::AUDITFAILURE];
            $historyEloq->fill($historyEdit);
            $historyEloq->save();
            //退还管理员人工充值额度
            $auditFlow = auditFlow::where('id', $rechargeLog->audit_flow_id)->first();
            $adminFundDataEdit = ['fund' => $newFund];
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $this->inputs['auditor_note']);
            $adminFundData->fill($adminFundDataEdit);
            $adminFundData->save();
            //返还额度后  插入artificial_recharge_log 记录表
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

    public function auditFlowEdit($eloq, $partnerAdmin, $auditor_note)
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
     * 发送站内消息给申请人
     * @param  int $adminId 申请人id
     * @param  string $message 消息内容
     * @return void
     */
    public function sendMessage($adminId, $message)
    {
        $messageClass = new InternalNoticeMessage();
        $type = NoticeMessage::AUDIT;
        $admin = PartnerAdminUsers::select('id', 'group_id')->find($adminId);
        if (!is_null($admin)) {
            $messageClass->insertMessage($type, $message, $admin->toArray());
        }
    }
}
