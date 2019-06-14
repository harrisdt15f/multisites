<?php

namespace App\Http\Controllers\BackendApi\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupCreateRequest;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupDestroyRequest;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupEditRequest;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupSpecificGroupUsersRequesthe;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PartnerAdminGroupController extends BackEndApiMainController
{
    protected $postUnaccess = ['id', 'updated_at', 'created_at']; //不需要接收的字段

    protected $eloqM = 'Admin\BackendAdminAccessGroup';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = $this->eloqM::all()->toArray();
        return $this->msgOut(true, $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(PartnerAdminGroupCreateRequest $request)
    {
        $inputDatas = $request->validated();
        try {
            $data['platform_id'] = $this->currentPlatformEloq->platform_id;
            $data['group_name'] = $inputDatas['group_name'];
            $data['role'] = $inputDatas['role'];
            $role = $inputDatas['role'] == '*' ? Arr::wrap($inputDatas['role']) : Arr::wrap(json_decode($inputDatas['role'],
                true));
            $objPartnerAdminGroup = new $this->eloqM;
            $objPartnerAdminGroup->fill($data);
            $objPartnerAdminGroup->save();
            //检查是否有人工充值权限
            $fundOperationCriteriaEloq = BackendSystemMenu::select('id')->where('route', '/manage/recharge')->first();
            $isManualRecharge = in_array($fundOperationCriteriaEloq['id'], $role);
            //如果有人工充值权限   添加 backend_admin_recharge_permit_groups 表
            if ($isManualRecharge === true) {
                $fundOperationGroup = new BackendAdminRechargePermitGroup();
                $fundOperationData = [
                    'group_id' => $objPartnerAdminGroup->id,
                    'group_name' => $objPartnerAdminGroup->group_name,
                ];
                $fundOperationGroup->fill($fundOperationData);
                $fundOperationGroup->save();
            }
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
        $partnerMenuObj = new BackendSystemMenu();
        $partnerMenuObj->createMenuDatas($objPartnerAdminGroup->id, $role);
        return $this->msgOut(true, $data);
    }

    protected function accessOnlyColumn()
    {
        $partnerAdminAccess = new $this->eloqM();
        $column = $partnerAdminAccess->getTableColumns();
        $column = array_values(array_diff($column, $this->postUnaccess));
        return $column;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function edit(PartnerAdminGroupEditRequest $request)
    {
        $inputDatas = $request->validated();
        $id = $inputDatas['id'];
        $datas = $this->eloqM::find($id);
        $role = $inputDatas['role'] == '*' ? Arr::wrap($inputDatas['role']) : Arr::wrap(json_decode($inputDatas['role'],
            true));
        if (!is_null($datas)) {
            DB::beginTransaction();
            $datas->group_name = $inputDatas['group_name'];
            $datas->role = $inputDatas['role'];
            //#####################################################################################
            try {
                $datas->save();
                //检查提交的权限中 是否有 人工充值权限  $isManualRecharge
                $fundOperationCriteriaEloq = BackendSystemMenu::select('id')->where('route', '/manage/recharge')->first();
                $isManualRecharge = in_array($fundOperationCriteriaEloq->id, $role, true);
                //检查资金操作权限表是 否已存在 在当前用户组  $check
                $fundOperatinEloq = BackendAdminRechargePermitGroup::where('group_id', $datas->id)->first();
                $fundOperation = new BackendAdminRechargePocessAmount();
                $fundOperationDatas = [];
                if ($isManualRecharge === true) {
                    //如果之前没有就需要添加到 backend_admin_recharge_pocess_amounts 表里面 如果之前有表示已添加
                    if (is_null($fundOperatinEloq)) {
                        $fundOperationData = [
                            'group_id' => $datas->id,
                            'group_name' => $datas->group_name,
                        ];
                        $fundOperationGroup = new BackendAdminRechargePermitGroup();
                        $fundOperationGroup->fill($fundOperationData);
                        $fundOperationGroup->save();
                        //要添加到 fundoperation 里面的 当前 资金权限的id  有的 管理员 取出来
                        if ($fundOperationGroup->admins()->exists()) {
                            $partnerAdminsEloq = $fundOperationGroup->admins;
                            foreach ($partnerAdminsEloq as $adminData) {
                                $fundOperationData = [
                                    'admin_id' => $adminData->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ];
                                $fundOperationDatas[] = $fundOperationData;
                            }
                            $fundOperation->insert($fundOperationDatas);
                        }
                    }
                } else {
                    // 提交的时候是没有资金操作权限 然后
                    //之前有资金操作权限 所以 需要从 fundoperation 表里面删除
                    if (!is_null($fundOperatinEloq)) {
                        if ($fundOperatinEloq->admins()->exists()) {
                            $partnerAdminsEloq = $fundOperatinEloq->admins;
                            $adminsData = $partnerAdminsEloq->toArray();
                            $partnerAdminsIdArr = array_column($adminsData, 'id');
                            $fundOperation->whereIn('admin_id', $partnerAdminsIdArr)->delete();
                        }
                        BackendAdminRechargePermitGroup::where('group_id', $datas->id)->delete();
                    }
                }
                DB::commit();
                return $this->msgOut(true, $datas->toArray());
            } catch (Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100200');
        }
    }

    /**
     * 删除组管理员角色
     * @return JsonResponse
     */
    public function destroy(PartnerAdminGroupDestroyRequest $request):  ? JsonResponse
    {
        $inputDatas = $request->validated();
        $id = $inputDatas['id'];
        $datas = $this->eloqM::where([
            ['id', '=', $id],
            ['group_name', '=', $inputDatas['group_name']],
        ])->first();
        if (!is_null($datas)) {
            try {
                if ($datas->adminUsers()->exists()) {
                    $datas->adminUsers()->delete();
                }
                $datas->delete();
                //检查是否有人工充值权限
                $role = $datas->role == '*' ? Arr::wrap($datas->role) : Arr::wrap(json_decode($datas->role, true));
                $fundOperation = BackendSystemMenu::select('id')->where('route', '/manage/recharge')->first()->toArray();
                $isManualRecharge = in_array($fundOperation['id'], $role, true);
                //如果有有人工充值权限   删除  FundOperation  BackendAdminRechargePermitGroup 表
                if ($isManualRecharge === true) {
                    $fundOperationGroup = new BackendAdminRechargePermitGroup();
                    $fundOperationGroup->where('group_id', $id)->delete();
                    //需要删除的资金表 admin
                    $fundOperationEloq = new BackendAdminRechargePocessAmount();
                    $adminsData = BackendAdminUser::select('id')->where('group_id', $id)->get();
                    $admins = array_column($adminsData->toArray(), 'id');
                    if (!is_null($adminsData)) {
                        $fundOperationEloq->whereIn('admin_id', $admins)->delete();
                    }
                }
                return $this->msgOut(true);
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100201');
        }
    }

    public function specificGroupUsers(PartnerAdminGroupSpecificGroupUsersRequesthe $request)
    {
        $inputDatas = $request->validated();
        $accessGroupEloq = $this->eloqM::find($inputDatas['id']);
        if (!is_null($accessGroupEloq)) {
            $data = $accessGroupEloq->adminUsers->toArray();
            return $this->msgOut(true, $data);
        } else {
            return $this->msgOut(false, [], '100202');
        }
    }

}
