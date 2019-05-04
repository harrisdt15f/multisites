<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\adminAdmitedFlows;
use App\models\FundOperation;
use App\models\FundOperationGroup;
use App\models\PartnerAdminUsers;
use App\models\PartnerSysConfigures;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FundOperationController extends ApiMainController
{
    public function users()
    {

        $rule = [
            'name' => 'numeric',
            'group_id' => 'numeric',
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
        return $this->msgout(true, $data);
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
        $comment = '[人工充值额度操作]==> +' . $this->inputs['fund'] . '额度';
        $flowsData = [
            'super_admin_id' => $this->partnerAdmin->id,
            'super_admin_name' => $this->partnerAdmin->name,
            'admin_id' => $admin_user->id,
            'admin_name' => $admin_user->name,
            'comment' => $comment,
        ];
        DB::beginTransaction();
        try {
            FundOperation::where('admin_id', $this->inputs['id'])->increment('fund', $this->inputs['fund']);
            $adminAdmitedFlows = new adminAdmitedFlows();
            $adminAdmitedFlows->fill($flowsData);
            $adminAdmitedFlows->save();
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
