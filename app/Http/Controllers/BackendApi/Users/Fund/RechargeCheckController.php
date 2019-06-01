<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\AccountChange;
use App\Lib\Common\FundOperationRecharge;
use App\Models\Admin\Fund\FundOperation;
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
            //用户金额表
            $userData = UserHandleModel::where('id', $rechargeLog->user_id)->with('account')->first();
            $balance = $userData->account->balance + $rechargeLog['amount'];
            $UserAccountsEdit = ['balance' => $balance];
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $this->inputs['auditor_note']);
            $UserAccounts = HandleUserAccounts::where('user_id', $rechargeLog->user_id)->first();
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
            $accountChangeClass->addData($accountChangeReportEloq, $userData, $rechargeLog['amount'], $UserAccounts->balance, $balance, $accountChangeTypeEloq, $accountChangeTypeEloq);
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
}
