<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\ArtificialRechargeLog;
use App\models\FundOperation;
use App\models\FundOperationGroup;
use App\models\PartnerAdminUsers;
use App\models\PartnerSysConfigures;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FundOperationController extends ApiMainController
{
    public function admins()
    {
        $rule = [
            'name' => 'string',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 200);
        }
        $groupArr = FundOperationGroup::select('group_id')->get()->toArray();
        $group = array_column($groupArr, 'group_id');
        $this->inputs['extra_where']['method'] = 'whereIn';
        $this->inputs['extra_where']['key'] = 'group_id';
        $this->inputs['extra_where']['value'] = $group;
        $eloqM = new PartnerAdminUsers();
        $fixedJoin = 1; //number of joining tables
        $withTable = 'operateAmount';
        $searchAbleFields = ['name', 'group_id'];
        $withSearchAbleFields = ['fund'];
        $orderFields = 'id';
        $orderFlow = 'asc';
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields, $orderFields, $orderFlow);
        $SysConfiguresEloq = PartnerSysConfigures::where('sign', 'admin_recharge_daily_limit')->first();
        $finalData['admin_user'] = $data;
        $finalData['dailyFundLimit'] = $SysConfiguresEloq['value'];
        return $this->msgout(true, $finalData);
    }
    public function addFund()
    {
        $rule = [
            'id' => 'required|numeric',
            'fund' => 'required|numeric|gt:0',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 200);
        }
        $admin_user = PartnerAdminUsers::find($this->inputs['id']);
        if (is_null($admin_user)) {
            return $this->msgout(false, [], '管理员不存在');
        }
        $comment = '[人工充值额度操作]==> ' . $this->inputs['fund'];
        $partnerAdmin = $this->partnerAdmin;
        $type = 0;
        $in_out = 1;
        $ArtificialRechargeLog = new ArtificialRechargeLog();
        DB::beginTransaction();
        try {
            FundOperation::where('admin_id', $this->inputs['id'])->increment('fund', $this->inputs['fund']);
            $this->insertOperationDatas($ArtificialRechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $admin_user->id, $admin_user->name, $this->inputs['fund'], $comment);
            DB::commit();
            return $this->msgout(true, [], '增加额度成功');
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
    public function everyDayFund()
    {
        $rule = [
            'fund' => 'required|numeric',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 200);
        }
        try {
            $SysConfiguresEloq = PartnerSysConfigures::where('sign', 'admin_recharge_daily_limit')->first();
            $editData = ['value' => $this->inputs['fund']];
            $SysConfiguresEloq->fill($editData);
            $SysConfiguresEloq->save();
            return $this->msgout(true, [], '设置额度成功');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
}
