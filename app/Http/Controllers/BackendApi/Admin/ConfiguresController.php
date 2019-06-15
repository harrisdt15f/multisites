<?php

namespace App\Http\Controllers\BackendApi\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\ConfiguresAddRequest;
use App\Http\Requests\Backend\Admin\ConfiguresConfigSwitchRequest;
use App\Http\Requests\Backend\Admin\ConfiguresDeleteRequest;
use App\Http\Requests\Backend\Admin\ConfiguresEditRequest;
use App\Http\Requests\Backend\Admin\ConfiguresGenerateIssueTimeRequest;
use App\Http\Requests\Backend\Admin\ConfiguresGetSysConfigureValueRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConfiguresController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\SystemConfiguration';

    /**
     * 获取全部配置
     * @return JsonResponse
     */
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

    /**
     * 添加配置
     * @param  ConfiguresAddRequest $request
     * @return JsonResponse
     */
    public function add(ConfiguresAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $addDatas = $inputDatas;
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
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 修改配置
     * @param  ConfiguresEditRequest $request [description]
     * @return JsonResponse
     */
    public function edit(ConfiguresEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $checkSign = $this->eloqM::where('sign', $inputDatas['sign'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($checkSign === true) {
            return $this->msgOut(false, [], '100700');
        }
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        $this->editAssignment($pastDataEloq, $inputDatas);
        try {
            $pastDataEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除配置
     * @param  ConfiguresDeleteRequest $request [description]
     * @return JsonResponse
     */
    public function delete(ConfiguresDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $this->eloqM::find($inputDatas['id'])->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }

    }

    /**
     * 配置状态开关 0关  1开
     * @param  ConfiguresConfigSwitchRequest $request
     * @return JsonResponse
     */
    public function configSwitch(ConfiguresConfigSwitchRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::find($inputDatas['id']);
        try {
            $pastData->status = $inputDatas['status'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }

    }

    /**
     * 配置获取奖期时间
     * @param  ConfiguresGenerateIssueTimeRequest $request [description]
     * @return JsonResponse
     */
    public function generateIssueTime(ConfiguresGenerateIssueTimeRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::where('sign', 'generate_issue_time')->first();
        try {
            if ($pastDataEloq !== null) {
                $pastDataEloq->value = $inputDatas['value'];
                $pastDataEloq->save();
                if (Cache::has('generateIssueTime')) {
                    Cache::forget('generateIssueTime');
                }
            } else {
                $bool = $this->createIssueConfigure($inputDatas['value']);
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

    /**
     * 获取某个配置的值
     * @param  ConfiguresGetSysConfigureValueRequest $request
     * @return JsonResponse
     */
    public function getSysConfigureValue(ConfiguresGetSysConfigureValueRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $sysConfiguresEloq = new $this->eloqM;
        $time = $sysConfiguresEloq->getConfigValue($inputDatas['key']);
        return $this->msgOut(true, $time);
    }
}
