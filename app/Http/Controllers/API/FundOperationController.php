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
            return $this->msgOut(false, [], 400, $validator->errors()->first());
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
        return $this->msgOut(true, $finalData);
    }
    public function addFund()
    {
        $rule = [
            'id' => 'required|numeric',
            'fund' => 'required|numeric|gt:0',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], 400, $validator->errors()->first());
        }
        $admin_user = PartnerAdminUsers::find($this->inputs['id']);
        if (is_null($admin_user)) {
            return $this->msgOut(false, [], '101300');
        }
        $FundOperationAdmin = FundOperation::where('admin_id', $this->inputs['id'])->first();
        $newFund = $FundOperationAdmin->fund + $this->inputs['fund'];
        $AdminEditData = ['fund' => $newFund];
        $comment = '[人工充值额度操作]==>+' . $this->inputs['fund'] . '|[目前额度]==>' . $newFund;
        $partnerAdmin = $this->partnerAdmin;
        $type = ArtificialRechargeLog::SUPERADMIN;
        $in_out = ArtificialRechargeLog::INCREMENT;
        $ArtificialRechargeLog = new ArtificialRechargeLog();
        DB::beginTransaction();
        try {
            $FundOperationAdmin->fill($AdminEditData);
            $FundOperationAdmin->save();
            $this->insertOperationDatas($ArtificialRechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $admin_user->id, $admin_user->name, $this->inputs['fund'], $comment, null);
            DB::commit();
            return $this->msgOut(true, [], 200);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
    public function everyDayFund()
    {
        $rule = [
            'fund' => 'required|numeric',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], 400, $validator->errors()->first());
        }
        try {
            $SysConfiguresEloq = PartnerSysConfigures::where('sign', 'admin_recharge_daily_limit')->first();
            $editData = ['value' => $this->inputs['fund']];
            $SysConfiguresEloq->fill($editData);
            $SysConfiguresEloq->save();
            return $this->msgOut(true, [], 200);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
    public function fundChangeLog()
    {
        $validator = Validator::make($this->inputs, [
            'admin_id' => 'required|numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], 400, $validator->errors()->first());
        }
        $datas = ArtificialRechargeLog::where(function ($query) {
            $query->where('admin_id', $this->inputs['admin_id'])
                ->where('created_at', '>', $this->inputs['start_time'])
                ->where('created_at', '<', $this->inputs['end_time']);
        })->get()->toArray();
        return $this->msgout(true, $datas);
    }
}
