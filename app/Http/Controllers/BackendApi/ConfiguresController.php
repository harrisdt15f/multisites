<?php

namespace App\Http\Controllers\BackendApi;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ConfiguresController extends ApiMainController
{
    protected $eloqM = 'PartnerSysConfigures';

    //获取全部配置
    public function getConfiguresList()
    {
        $partnerSysConfigEloq = $this->eloqM::select('id', 'parent_id', 'pid', 'sign', 'name', 'description', 'value', 'add_admin_id', 'last_update_admin_id', 'status', 'created_at', 'updated_at')->get();
        $data = [];
        foreach ($partnerSysConfigEloq as $partnerSysConfigItem) {
            if ($partnerSysConfigItem->parent_id === 0) {
                $data[$partnerSysConfigItem->id] = $partnerSysConfigItem->toArray();
                $data[$partnerSysConfigItem->id]['sub'] = $partnerSysConfigItem->childs->toArray();
            }
        }
        return $this->msgOut(true, $data);
    }

    //添加配置
    public function add()
    {
        $rule = [
            'parent_id' => 'required|numeric',
            'sign' => 'required',
            'name' => 'required',
            'description' => 'required',
            'value' => 'required', //父级的时候是不需要传送 value 值
        ];
        if (isset($this->inputs['parent_id']) && $this->inputs['parent_id'] === 0) {
            unset($rule['value']);
        }
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $addDatas = $this->inputs;
        $addDatas['pid'] = $this->currentPlatformEloq->platform_id;
        $adminId = $this->partnerAdmin->id;
        $addDatas['add_admin_id'] = $adminId;
        $addDatas['last_update_admin_id'] = $adminId;
        $addDatas['status'] = 1;
        $pastData = $this->eloqM::where('sign', $this->inputs['sign'])->first();
        if (is_null($pastData)) {
            try {
                $configure = new $this->eloqM();
                $configure->fill($addDatas);
                $configure->save();
                return $this->msgOut(true, [], '200');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100700');
        }
    }

    //修改配置
    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'parent_id' => 'required|numeric',
            'sign' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('sign', $this->inputs['sign'])->where('id', '!=', $this->inputs['id'])->first();
        if (is_null($pastData)) {
            $editDataEloq = $this->eloqM::find($this->inputs['id']);
            $editDatas = $this->inputs;
            unset($editDatas['id'], $editDatas['parent_id']);
            //不是顶级可修改值
            if ($this->inputs['parent_id'] === 0) {
                unset($editDatas['value']);
            }
            $this->editAssignment($editDataEloq, $editDatas);
            try {
                $editDataEloq->save();
                return $this->msgOut(true, [], '200');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100700');
        }
    }

    //删除配置
    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (!is_null($pastData)) {
            try {
                $this->eloqM::where('id', $this->inputs['id'])->delete();
                return $this->msgOut(true, [], '200');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100701');
        }
    }

    //配置状态开关 0关  1开
    public function configSwitch()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'parent_id' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        if ($this->inputs['parent_id'] == 0) {
            return $this->msgOut(false, [], '100702');
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (!is_null($pastData)) {
            if ($this->inputs['status'] == 0) {
                $editStatus = 1;
            } else {
                $editStatus = 0;
            }
            $pastData->status = $editStatus;
            try {
                $pastData->save();
                return $this->msgOut(true, [], '200');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100701');
        }
    }
}
