<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\ArtificialRechargeLog;
use App\models\AuditFlow;
use App\models\FundOperation;
use App\models\UserHandleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArtificialRechargeController extends ApiMainController
{
    protected $eloqM = 'UserHandleModel';
    public function users()
    {
        $fixedJoin = 1;
        $withTable = 'account';
        $searchAbleFields = ['username', 'type', 'vip_level', 'is_tester', 'frozen_type', 'prize_group', 'level_deep', 'register_ip'];
        $withSearchAbleFields = ['balance'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $this->msgOut(true, $data);
    }
    public function recharge()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'amount' => 'required|numeric|gt:0',
            'apply_note' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], 400, $validator->errors()->first());
        }
        $userData = UserHandleModel::find($this->inputs['id']);
        if (is_null($userData)) {
            return $this->msgOut(false, [], 101100);
        }
        $partnerAdmin = $this->partnerAdmin;
        $adminFundData = FundOperation::where('admin_id', $partnerAdmin->id)->first();
        if (is_null($adminFundData)) {
            return $this->msgOut(false, [], 101101);
        }
        $adminOperationFund = $adminFundData->fund;
        //可操作额度小于充值额度
        if ($adminOperationFund < $this->inputs['amount']) {
            return $this->msgOut(false, [], 101102);
        }
        DB::beginTransaction();
        try {
            $newFund = $adminOperationFund - $this->inputs['amount'];
            $adminFundEdit = ['fund' => $newFund];
            $ArtificialRechargeLog = new ArtificialRechargeLog();
            $type = ArtificialRechargeLog::ADMIN;
            $in_out = ArtificialRechargeLog::DECREMENT;
            $comment = '[给用户人工充值]==>-' . $this->inputs['amount'] . '|[目前额度]==>' . $newFund;
            $adminFundData->fill($adminFundEdit);
            $adminFundData->save();
            $AuditFlowID = $this->insertAuditFlow($partnerAdmin->id, $partnerAdmin->name, $this->inputs['apply_note']);
            $this->insertOperationDatas($ArtificialRechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $userData->id, $userData->nickname, $this->inputs['amount'], $comment, $AuditFlowID);
            DB::commit();
            return $this->msgOut(true, [], 200, '操作成功，请等待管理员审核');
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //插入审核表
    public function insertAuditFlow($admin_id, $admin_name, $apply_note)
    {
        $insertData = [
            'admin_id' => $admin_id,
            'admin_name' => $admin_name,
            'apply_note' => $apply_note,
        ];
        $AuditFlow = new AuditFlow();
        $AuditFlow->fill($insertData);
        $AuditFlow->save();
        return $AuditFlow->id;
    }
}
