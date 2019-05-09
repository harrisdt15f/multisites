<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\AccountChangeReport;
use App\models\AuditFlow;
use App\models\FundOperation;
use App\models\UserAccounts;
use App\models\UserHandleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RechargeCheckController extends ApiMainController
{
    protected $eloqM = 'ArtificialRechargeLog';

    public function detail()
    {
        $fixedJoin = 1;
        $withTable = 'auditFlow';
        $withSearchAbleFields = ['apply_note'];
        $searchAbleFields = ['status', 'type'];
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
        // 人工充值记录表
        $RechargeLog = $this->eloqM::find($this->inputs['id']);
        $RechargeLogEdit = ['status' => 1];
        // 审核表
        $auditFlow = auditFlow::where('id', $RechargeLog->audit_flow_id)->first();
        $RechargeLog = $this->eloqM::find($this->inputs['id']);
        if ($RechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        DB::beginTransaction();
        try {
            //用户金额表
            $UserAccounts = UserAccounts::where('user_id', $RechargeLog->user_id)->lockForUpdate()->first();
            $userData = UserHandleModel::where('id', $RechargeLog->user_id)->with('account')->first()->toArray();
            $balance = $userData['account']['balance'] + $RechargeLog['amount'];
            $UserAccountsEdit = ['balance' => $balance];
            $RechargeLog->fill($RechargeLogEdit);
            $RechargeLog->save();
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $this->inputs['auditor_note']);
            $UserAccounts->fill($UserAccountsEdit);
            $UserAccounts->save();
            //用户帐变表
            $this->insertChangeReport($userData, $RechargeLog['amount'], $balance);
            DB::commit();
            return $this->msgOut(true, [], '200');
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
        $RechargeLog = $this->eloqM::find($this->inputs['id']);
        if ($RechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        $RechargeLogEdit = ['status' => 2];
        $auditFlow = auditFlow::where('id', $RechargeLog->audit_flow_id)->first();
        $adminFundData = FundOperation::where('admin_id', $RechargeLog->admin_id)->first();
        $newFund = $adminFundData->fund + $RechargeLog->amount;
        $adminFundDataEdit = ['fund' => $newFund];
        $RechargeLogeloqM = new $this->eloqM;
        $type = 0;
        $in_out = 1;
        $comment = '[充值审核失败额度返还]==>+' . $RechargeLog['amount'] . '|[目前额度]==>' . $newFund;
        DB::beginTransaction();
        try {
            $RechargeLog->fill($RechargeLogEdit);
            $RechargeLog->save();
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $this->inputs['auditor_note']);
            $adminFundData->fill($adminFundDataEdit);
            $adminFundData->save();
            $this->insertOperationDatas($RechargeLogeloqM, $type, $in_out, null, null, $auditFlow->admin_id, $auditFlow->admin_name, $RechargeLog->amount, $comment, null);
            DB::commit();
            return $this->msgOut(true, [], '200');
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

    public function insertChangeReport($user, $amount, $balance)
    {
        //type_sign
        //type_name
        $insertData = [
            'sign' => $user['sign'],
            'user_id' => $user['id'],
            'top_id' => $user['top_id'],
            'parent_id' => $user['parent_id'],
            'rid' => $user['rid'],
            'username' => $user['username'],
            'type_sign' => '充值',
            'type_name' => 'recharge',
            'amount' => $amount,
            'before_balance' => $user['account']['balance'],
            'balance' => $balance,
        ];
        $eloq = new AccountChangeReport();
        $eloq->fill($insertData);
        $eloq->save();
    }
}
