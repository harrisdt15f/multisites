<?php

namespace App\Http\Controllers\BackendApi\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfiguresController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\SystemConfiguration';

    //获取全部配置
    public function getConfiguresList(): JsonResponse
    {
        $partnerSysConfigEloq = $this->eloqM::select('id', 'parent_id', 'pid', 'sign', 'name', 'description', 'value', 'add_admin_id', 'last_update_admin_id', 'status', 'created_at', 'updated_at')->where('display', 1)->get();
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
            'sign' => 'required|unique:system_configurations,sign',
            'name' => 'required',
            'description' => 'required',
        ];
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
            'id' => 'required|numeric|exists:system_configurations,id',
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
        if ($checkSign === true) {
            return $this->msgOut(false, [], '100700');
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
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
            'id' => 'required|numeric|exists:system_configurations,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
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
            'id' => 'required|numeric|exists:system_configurations,id',
            'parent_id' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        if ($this->inputs['parent_id'] == 0) {
            return $this->msgOut(false, [], '100701');
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
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
        try {
            if (!is_null($pastDataEloq)) {
                $pastDataEloq->value = $this->inputs['value'];
                $pastDataEloq->save();
                if (Cache::has('generateIssueTime')) {
                    Cache::forget('generateIssueTime');
                }
            } else {
                $bool = $this->createIssueConfigure($this->inputs['value']);
                if ($bool === false) {
                    return $this->msgOut(false, [], '100702');
                }
            }
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 生成奖期配置
     * @param  date $time 自动生成奖期的时间
     * @return void
     */
    public function createIssueConfigure($time)
    {
        DB::beginTransaction();
        try {
            //生成父级 奖期相关 系统配置
            $adminId = $this->partnerAdmin->id;
            $addData = [
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'issue',
                'name' => '奖期相关',
                'description' => '奖期相关的所有配置',
                'add_admin_id' => $adminId,
                'last_update_admin_id' => $adminId,
                'status' => 1,
                'display' => 0,
            ];
            $configureEloq = new $this->eloqM();
            $configureEloq->fill($addData);
            $configureEloq->save();
            //生成子级 生成奖期时间 系统配置
            $data = [
                'parent_id' => $configureEloq->id,
                'pid' => 1,
                'sign' => 'generate_issue_time',
                'name' => '生成奖期时间',
                'description' => '每天自动生成奖期的时间',
                'value' => $time,
                'add_admin_id' => $adminId,
                'last_update_admin_id' => $adminId,
                'status' => 1,
                'display' => 0,
            ];
            $issueTimeEloq = new $this->eloqM();
            $issueTimeEloq->fill($data);
            $issueTimeEloq->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
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
