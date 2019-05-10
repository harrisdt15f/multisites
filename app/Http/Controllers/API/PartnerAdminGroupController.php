<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\FundOperation;
use App\models\FundOperationGroup;
use App\models\PartnerAdminUsers;
use App\models\PartnerMenus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PartnerAdminGroupController extends ApiMainController
{
    protected $postUnaccess = ['id', 'updated_at', 'created_at']; //不需要接收的字段

    protected $eloqM = 'PartnerAdminGroupAccess';

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
    public function create()
    {
        $validator = Validator::make($this->inputs, [
            'group_name' => 'required|unique:partner_access_group',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
//        unique:books,label
        $data['platform_id'] = $this->currentPlatformEloq->platform_id;
        $data['group_name'] = $this->inputs['group_name'];
        $data['role'] = $this->inputs['role'];
        $role = json_decode($this->inputs['role']); //[1,2,3,4,5]
        $objPartnerAdminGroup = new $this->eloqM;
        $objPartnerAdminGroup->fill($data);
        //检查是否有人工充值权限
        $FundOperation = PartnerMenus::select('id')->where('route', '/manage/recharge')->first()->toArray();
        $isManualRecharge = in_array($FundOperation['id'], $role);
        try {
            $objPartnerAdminGroup->save();
            if ($isManualRecharge === true) {
                $FundOperationGroup = new FundOperationGroup();
                $FundOperationData = [
                    'group_id' => $objPartnerAdminGroup->id,
                    'group_name' => $objPartnerAdminGroup->group_name,
                ];
                $FundOperationGroup->fill($FundOperationData);
                $FundOperationGroup->save();
            }
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
        $partnerMenuObj = new PartnerMenus();
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
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'group_name' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $id = $request->get('id');
        $datas = $this->eloqM::find($id);
        //检查提交的权限中 是否有 人工充值权限  $isManualRecharge
        $role = json_decode($this->inputs['role']);
        $FundOperation = PartnerMenus::select('id')->where('route', '/manage/recharge')->first()->toArray();
        $isManualRecharge = in_array($FundOperation['id'], $role);
        if (!is_null($datas)) {
            DB::beginTransaction();
            $datas->group_name = $request->get('group_name');
            $datas->role = $request->get('role');
            try {
                $FundOperation = new FundOperation();
                $time = date('Y-m-d H:i:s', time());
                $fundOperationDatas = [];
                $datas->save();
                $data = $datas->toArray();
                //检查资金操作权限表是否存在此用户组  $check
                $FundOperationGroup = new FundOperationGroup();
                $check = $FundOperationGroup->where('group_id', $data['id'])->first();
                if ($isManualRecharge === true) {
                    if (is_null($check)) {
                        //需要删除的资金表 admin
                        $admins = PartnerAdminUsers::where('group_id', $id)->get()->toArray();
                        foreach ($admins as $k => $v) {
                            $fundOperationData = [
                                'admin_id' => $v['id'],
                                'created_at' => $time,
                            ];
                            $fundOperationDatas[] = $fundOperationData;
                        }
                        $FundOperationData = [
                            'group_id' => $data['id'],
                            'group_name' => $data['group_name'],
                        ];
                        $FundOperationGroup->fill($FundOperationData);
                        $FundOperationGroup->save();
                        $FundOperation->insert($fundOperationDatas);
                    }
                } else {
                    if (!is_null($check)) {
                        $adminsData = PartnerAdminUsers::select('id')->where('group_id', $id)->get()->toArray();
                        $admins = array_column($adminsData, 'id');
                        $FundOperationGroup->where('group_id', $data['id'])->delete();
                        $FundOperation->whereIn('admin_id', $admins)->delete();
                    }
                }
                DB::commit();
                return $this->msgOut(true, $data);
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
    public function destroy()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'group_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $id = $this->inputs['id'];
        $datas = $this->eloqM::where([
            ['id', '=', $id],
            ['group_name', '=', $this->inputs['group_name']],
        ])->first();
        //检查是否有人工充值权限
        $role = Arr::wrap(json_decode($datas->role, true));
        $FundOperation = PartnerMenus::select('id')->where('route', '/manage/recharge')->first()->toArray();
        $isManualRecharge = in_array($FundOperation['id'], $role, true);
        if (!is_null($datas)) {
            try {
                if ($isManualRecharge === true) {
                    $FundOperation = new FundOperation();
                    $FundOperationGroup = new FundOperationGroup();
                    //需要删除的资金表 admin
                    $adminsData = PartnerAdminUsers::select('id')->where('group_id', $id)->get()->toArray();
                    $admins = array_column($adminsData, 'id');
                    $FundOperation->whereIn('admin_id', $admins)->delete();
                    $FundOperationGroup->where('group_id', $id)->delete();
                }
                PartnerAdminUsers::where('group_id', $datas->id)->update(['group_id' => null]);
                $datas->delete();
                return $this->msgOut(true, []);
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100201');
        }
    }

    public function specificGroupUsers()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $accessGroupEloq = $this->eloqM::find($this->inputs['id']);
        if (!is_null($accessGroupEloq)) {
            $data = $accessGroupEloq->adminUsers->toArray();
            return $this->msgOut(true, $data);
        } else {
            return $this->msgOut(false, [], '100202');
        }
    }

}
