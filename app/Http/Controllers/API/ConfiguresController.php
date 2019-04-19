<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ConfiguresController extends ApiMainController
{
    protected $eloqM = 'PartnerSysConfigures';

    //获取全部配置
    public function getConfiguresList()
    {
        $all = $this->eloqM::all()->toArray();
        foreach ($all as $k1 => $v1) {
            //##########
            if ($all[$k1]['parent_id'] == 0) {
                $data[] = $all[$k1];
            } else {
                foreach ($data as $k2 => $v2) {
                    if ($all[$k1]['parent_id'] == $data[$k2]['id']) {
                        $data[$k2]['sub'][] = $all[$k1];
                    }
                }
            }
        }
        return $this->msgout(true, $data);
    }

    //添加配置
    public function add()
    {

        $rule = [
            'parent_id' => 'required|numeric',
            'sign' => 'required',
            'name' => 'required',
            'description' => 'required',
        ];
        if (isset($this->inputs['parent_id']) && $this->inputs['parent_id'] === 0) {
            $rule['value'] = 'required';
        }
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
        $addDatas = [
            'parent_id' => $this->inputs['parent_id'],
            'sign' => $this->inputs['sign'],
            'name' => $this->inputs['name'],
            'description' => $this->inputs['description'],
            'value' => $this->inputs['value'],
            'pid' => $this->currentPlatformEloq->platform_id,
            'add_admin_id' => $this->partnerAdmin->id,
            'last_update_admin_id' => $this->partnerAdmin->id,
            'status' => 1,
        ];
        $pastData = $this->eloqM::where('sign', $this->inputs['sign'])->first();
        if (is_null($pastData)) {
            try {
                $configure = new $this->eloqM();
                $configure->fill($addDatas);
                $configure->save();
                return $this->msgout(true, [], '添加配置成功');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '该配置键名已存在', '0009');
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
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
        $pastData = $this->eloqM::where('sign', $this->inputs['sign'])->first();
        if (is_null($pastData)) {
            $editDataEloq = $this->eloqM::find($this->inputs['id']);
            $editDataEloq->sign = $this->inputs['sign'];
            $editDataEloq->name = $this->inputs['name'];
            $editDataEloq->description = $this->inputs['description'];
            //不是顶级可修改值
            if ($this->inputs['parent_id'] != 0) {
                $editDataEloq->value = $this->inputs['value'];
            }
            try {
                $editDataEloq->save();
                return $this->msgout(true, [], '修改配置成功');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '该配置键名已存在', '0009');
        }
    }

    //删除配置
    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (!is_null($pastData)) {
            try {
                $this->eloqM::where('id', $this->inputs['id'])->delete();
                return $this->msgout(true, [], '删除配置成功');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '该数据不存在', '0009');
        }
    }

    //配置状态开关 0关  1开
    public function switch()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'parent_id' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
        if ($this->inputs['parent_id'] == 0) {
            return $this->msgout(false, [], '主级配置不可修改状态', '0009');
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
                return $this->msgout(true, [], '修改配置状态成功');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '该配置不存在', '0009');
        }
    }

}