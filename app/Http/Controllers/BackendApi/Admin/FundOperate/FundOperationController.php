<?php

namespace App\Http\Controllers\BackendApi\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\FundOperationRecharge;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\SystemConfiguration;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FundOperationController extends BackEndApiMainController
{
    //额度管理列表
    public function admins(): JsonResponse
    {
        $rule = ['name' => 'string'];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $groupArr = BackendAdminRechargePermitGroup::select('group_id')->get()->toArray();
        $group = array_column($groupArr, 'group_id');
        $this->inputs['extra_where']['method'] = 'whereIn';
        $this->inputs['extra_where']['key'] = 'group_id';
        $this->inputs['extra_where']['value'] = $group;
        $eloqM = new BackendAdminUser();
        $fixedJoin = 1; //number of joining tables
        $withTable = 'operateAmount';
        $searchAbleFields = ['name', 'group_id'];
        $withSearchAbleFields = ['fund'];
        $orderFields = 'id';
        $orderFlow = 'asc';
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields, $orderFields, $orderFlow);
        $SysConfiguresEloq = SystemConfiguration::where('sign', 'admin_recharge_daily_limit')->first();
        $finalData['admin_user'] = $data;
        $finalData['dailyFundLimit'] = $SysConfiguresEloq['value'];
        return $this->msgOut(true, $finalData);
    }

    //给管理员添加人工充值额度
    public function addFund(): JsonResponse
    {
        $rule = [
            'id' => 'required|numeric',
            'fund' => 'required|numeric|gt:0',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $admin_user = BackendAdminUser::find($this->inputs['id']);
        if (is_null($admin_user)) {
            return $this->msgOut(false, [], '101300');
        }
        $FundOperationAdmin = BackendAdminRechargePocessAmount::where('admin_id', $this->inputs['id'])->first();
        if (is_null($FundOperationAdmin)) {
            return $this->msgOut(false, [], '101301');
        }
        //查看该管理员今日 手动添加的充值额度
        $checkFund = $this->rechargeFundToday($this->inputs['id'], $this->inputs['fund']);
        if ($checkFund['success'] === false) {
            return $this->msgOut(false, [], '', $checkFund['msg']);
        }
        DB::beginTransaction();
        try {
            $newFund = $FundOperationAdmin->fund + $this->inputs['fund'];
            $AdminEditData = ['fund' => $newFund];
            $FundOperationAdmin->fill($AdminEditData);
            $FundOperationAdmin->save();
            $comment = '[人工充值额度操作]==>+' . $this->inputs['fund'] . '|[目前额度]==>' . $newFund;
            $partnerAdmin = $this->partnerAdmin;
            $type = BackendAdminRechargehumanLog::SUPERADMIN;
            $in_out = BackendAdminRechargehumanLog::INCREMENT;
            $rechargeLog = new BackendAdminRechargehumanLog();
            $fundOperationClass = new FundOperationRecharge();
            $fundOperationClass->insertOperationDatas($rechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $admin_user->id, $admin_user->name, $this->inputs['fund'], $comment, null);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //设置每日的管理员人工充值额度
    public function everyDayFund(): JsonResponse
    {
        $rule = [
            'fund' => 'required|numeric',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $SysConfiguresEloq = SystemConfiguration::where('sign', 'admin_recharge_daily_limit')->first();
        if (is_null($SysConfiguresEloq)) {
            return $this->msgOut(false, [], '101302');
        }
        try {
            $editData = ['value' => $this->inputs['fund']];
            $SysConfiguresEloq->fill($editData);
            $SysConfiguresEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //查看管理员人工充值额度记录
    public function fundChangeLog(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'admin_id' => 'required|numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $datas = BackendAdminRechargehumanLog::where(function ($query) {
            $query->where('admin_id', $this->inputs['admin_id'])
                ->where('created_at', '>', $this->inputs['start_time'])
                ->where('created_at', '<', $this->inputs['end_time']);
        })->get()->toArray();
        return $this->msgout(true, $datas);
    }

    /**
     * 查看该管理员今日 手动添加的充值额度是否在限额内
     * @param  [int] $admin_id     [需要充值的管理员id]
     * @param  [folat] $rechargeFund [充值的额度]
     * @return [array]
     */
    public function rechargeFundToday($admin_id, $rechargeFund)
    {
        $maxRechargeFund = 90000;
        $where = [
            'type' => 1,
            'admin_id' => $admin_id,
        ];
        $rechargeFundToday = BackendAdminRechargehumanLog::where($where)->sum('amount');
        if (($rechargeFundToday + $rechargeFund) > $maxRechargeFund) {
            $restRechargeFund = $maxRechargeFund - $rechargeFundToday;
            return ['success' => false, 'msg' => '管理员每日手动添加的最大充值额度为' . $maxRechargeFund . ',目前剩余额度' . $restRechargeFund];
        }
        return ['success' => true];
    }
}
