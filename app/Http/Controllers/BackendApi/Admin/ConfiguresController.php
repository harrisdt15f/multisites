<?php

namespace App\Http\Controllers\BackendApi\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ConfiguresController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\PartnerSysConfigures';

    //获取全部配置
    public function getConfiguresList(): JsonResponse
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
    public function add(): JsonResponse
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
        $pastData = $this->eloqM::where('sign', $this->inputs['sign'])->exists();
        if ($pastData === true) {
            return $this->msgOut(false, [], '100700');
        }
        $addDatas = $this->inputs;
        $addDatas['pid'] = $this->currentPlatformEloq->platform_id;
        $adminId = $this->partnerAdmin->id;
        $addDatas['add_admin_id'] = $adminId;
        $addDatas['last_update_admin_id'] = $adminId;
        $addDatas['status'] = 1;
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //修改配置
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'parent_id' => 'required|numeric',
            'sign' => 'required',
            'name' => 'required',
            'description' => 'required',
            'value' => 'string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkSign = $this->eloqM::where('sign', $this->inputs['sign'])->where('id', '!=', $this->inputs['id'])->exists();
        if ($pastData === true) {
            return $this->msgOut(false, [], '100700');
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '100701');
        }
        $editDatas = $this->inputs;
        $this->editAssignment($pastDataEloq, $editDatas);
        try {
            $pastDataEloq->save();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除配置
    public function delete(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '100701');
        }
        try {
            $pastData->delete();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }

    }

    //配置状态开关 0关  1开
    public function configSwitch(): JsonResponse
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
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '100701');
        }
        try {
            $pastData->status = $this->inputs['status'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }

    }

    //配置获取奖期时间
    public function generateIssueTime()
    {
        $validator = Validator::make($this->inputs, [
            'value' => 'required|date_format:H:i',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::where('sign', 'generate_issue_time')->first();
        if (is_null($pastDataEloq)) {
            return $this->msgOut(false, [], '100703');
        }
        try {
            $pastDataEloq->value = $this->inputs['value'];
            $pastDataEloq->save();
            if (Cache::has('generateIssueTime')) {
                $generateIssueTime = Cache::forget('generateIssueTime');
            }
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //获取某个配置的值
    public function getSysConfigureValue()
    {
        $validator = Validator::make($this->inputs, [
            'key' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $sysConfiguresEloq = new $this->eloqM;
        $time = $sysConfiguresEloq->getConfigValue($this->inputs['key']);
        return $this->msgOut(true, $time);
    }
}
