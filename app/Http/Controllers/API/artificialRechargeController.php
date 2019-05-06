<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\adminAdmitedFlows;
use App\models\FundOperation;
use App\models\UserHandleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class artificialRechargeController extends ApiMainController
{
    protected $eloqM = 'UserHandleModel';
    public function users()
    {
        $fixedJoin = 1;
        $withTable = 'account';
        $searchAbleFields = ['username', 'type', 'vip_level', 'is_tester', 'frozen_type', 'prize_group', 'level_deep', 'register_ip'];
        $withSearchAbleFields = ['balance'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $this->msgout(true, $data);
    }
    public function recharge()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'amount' => 'required|numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors());
        }
        $userData = UserHandleModel::find($this->inputs['id']);
        if (is_null($userData)) {
            return $this->msgout(false, [], '需要充值的用户不存在');
        }
        $partnerAdmin = $this->partnerAdmin;
        $adminFundData = FundOperation::where('admin_id', $partnerAdmin->id)->first();
        if (is_null($adminFundData)) {
            return $this->msgout(false, [], '您目前没有充值额度');
        }
        $adminOperationFund = $adminFundData->fund;
        //可操作额度小于充值额度
        if ($adminOperationFund < $this->inputs['amount']) {
            return $this->msgout(false, [], '您的充值额度不足,目前可用充值额度为' . $adminOperationFund);
        }
        $newFund = $adminOperationFund - $this->inputs['amount'];
        $adminFundEdit = ['fund' => $newFund];
        $adminAdmitedFlows = new adminAdmitedFlows();
        $type = 1;
        $in_out = 0;
        $comment = '[给用户人工充值]==>' . $this->inputs['amount'];
        DB::beginTransaction();
        try {
            $adminFundData->fill($adminFundEdit);
            $adminFundData->save();
            $this->insertOperationDatas($adminAdmitedFlows, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $userData->id, $userData->nickname, $this->inputs['amount'], $comment);
            DB::commit();
            return $this->msgout(true, [], '操作成功，请等待管理员审核');
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
}
