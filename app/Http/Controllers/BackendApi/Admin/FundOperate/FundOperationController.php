<?php

namespace App\Http\Controllers\BackendApi\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationAddFundRequest;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationAdminsRequest;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationEveryDayFundRequest;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationFundChangeLogRequest;
use App\Lib\Common\FundOperationRecharge;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\SystemConfiguration;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FundOperationController extends BackEndApiMainController
{
    /**
     * 额度管理列表
     * @param  FundOperationAdminsRequest $request
     * @return JsonResponse
     */
    public function adminDetail(FundOperationAdminsRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $group = BackendAdminRechargePermitGroup::select('group_id')->pluck('group_id')->toArray();
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
        $sysConfiguresEloq = SystemConfiguration::where('sign', 'admin_recharge_daily_limit')->first();
        $finalData['admin_user'] = $data;
        $finalData['dailyFundLimit'] = $sysConfiguresEloq['value'];
        return $this->msgOut(true, $finalData);
    }

    /**
     * 给管理员添加人工充值额度
     * @param FundOperationAddFundRequest $request
     * @return JsonResponse
     */
    public function addFund(FundOperationAddFundRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $adminDataEloq = BackendAdminUser::find($inputDatas['id']);
        $fundOperationAdmin = BackendAdminRechargePocessAmount::where('admin_id', $inputDatas['id'])->first();
        if (is_null($fundOperationAdmin)) {
            return $this->msgOut(false, [], '101300');
        }
        //查看该管理员今日 手动添加的充值额度
        $checkFund = $this->rechargeFundToday($inputDatas['id'], $inputDatas['fund']);
        if ($checkFund['success'] === false) {
            return $this->msgOut(false, [], '', $checkFund['msg']);
        }
        DB::beginTransaction();
        try {
            $newFund = $fundOperationAdmin->fund + $inputDatas['fund'];
            $adminEditData = ['fund' => $newFund];
            $fundOperationAdmin->fill($adminEditData);
            $fundOperationAdmin->save();
            $partnerAdmin = $this->partnerAdmin;
            $type = BackendAdminRechargehumanLog::SUPERADMIN;
            $in_out = BackendAdminRechargehumanLog::INCREMENT;
            $rechargeLog = new BackendAdminRechargehumanLog();
            $comment = '[人工充值额度操作]==>+' . $inputDatas['fund'] . '|[目前额度]==>' . $newFund;
            FundOperationRecharge::insertOperationDatas($rechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $adminDataEloq->id, $adminDataEloq->name, $inputDatas['fund'], $comment, null);
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
     * 设置每日的管理员人工充值额度
     * @param  FundOperationEveryDayFundRequest $request
     * @return JsonResponse
     */
    public function everyDayFund(FundOperationEveryDayFundRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $sysConfiguresEloq = SystemConfiguration::where('sign', 'admin_recharge_daily_limit')->first();
        if (is_null($sysConfiguresEloq)) {
            return $this->msgOut(false, [], '101301');
        }
        try {
            $editData = ['value' => $inputDatas['fund']];
            $sysConfiguresEloq->fill($editData);
            $sysConfiguresEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 查看管理员人工充值额度记录
     * @param  FundOperationFundChangeLogRequest $request
     * @return JsonResponse
     */
    public function fundChangeLog(FundOperationFundChangeLogRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $datas = BackendAdminRechargehumanLog::where('admin_id', $inputDatas['admin_id'])->whereBetween('created_at', [$inputDatas['start_time'], $inputDatas['end_time']])->get()->toArray();
        return $this->msgout(true, $datas);
    }

    /**
     * 查看该管理员今日 手动添加的充值额度是否在限额内
     * @param  int     $adminId       [需要充值的管理员id]
     * @param  folat   $rechargeFund  [充值的额度]
     * @return array
     */
    public function rechargeFundToday($adminId, $rechargeFund)
    {
        $maxRechargeFund = 90000;
        $today = date('Y-m-d');
        $rechargeFundToday = BackendAdminRechargehumanLog::select('amount')->where('type', 1)->where('admin_id', $adminId)->whereDate('created_at', $today)->sum('amount');
        if (($rechargeFundToday + $rechargeFund) > $maxRechargeFund) {
            $restRechargeFund = $maxRechargeFund - $rechargeFundToday;
            return ['success' => false, 'msg' => '管理员每日手动添加的最大充值额度为' . $maxRechargeFund . ',目前剩余额度' . $restRechargeFund];
        }
        return ['success' => true];
    }
}
