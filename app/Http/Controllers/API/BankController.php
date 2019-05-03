<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class BankController extends ApiMainController
{
    protected $eloqM = 'Banks';
    public function detail()
    {
        $searchAbleFields = ['title', 'code', 'pay_type', 'status'];
        $banksDatas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgout(true, $banksDatas);
    }
    public function addBank()
    {
        $validator = Validator::make($this->inputs, [
            'title' => 'required|string',
            'code' => 'required|alpha',
            'pay_type' => 'required|numeric',
            'status' => 'required|in:0,1',
            'min_recharge' => 'required|numeric',
            'max_recharge' => 'required|numeric',
            'min_withdraw' => 'required|numeric',
            'max_withdraw' => 'required|numeric',
            'remarks' => 'required|string',
            'allow_user_level' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $addDatas = [
            'title' => $this->inputs['title'],
            'code' => $this->inputs['code'],
            'pay_type' => $this->inputs['pay_type'],
            'status' => $this->inputs['status'],
            'min_recharge' => $this->inputs['min_recharge'],
            'max_recharge' => $this->inputs['max_recharge'],
            'min_withdraw' => $this->inputs['min_withdraw'],
            'max_withdraw' => $this->inputs['max_withdraw'],
            'remarks' => $this->inputs['remarks'],
            'allow_user_level' => $this->inputs['allow_user_level'],
        ];
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgout(true, [], '添加银行成功');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
    public function editBank()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'title' => 'required|string',
            'code' => 'required|alpha',
            'status' => 'required|in:0,1',
            'min_recharge' => 'required|numeric',
            'max_recharge' => 'required|numeric',
            'min_withdraw' => 'required|numeric',
            'max_withdraw' => 'required|numeric',
            'remarks' => 'required|string',
            'allow_user_level' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $editDataEloq = $this->eloqM::find($this->inputs['id']);
        if (empty($editDataEloq)) {
            return $this->msgout(false, [], '银行id不存在');
        }
        $this->editAssignment($editDataEloq, $this->inputs);
        try {
            $editDataEloq->save();
            return $this->msgout(true, [], '修改银行成功');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
    public function deleteBank()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
        try {
            $this->eloqM::where('id', $this->inputs['id'])->delete();
            return $this->msgout(true, [], '删除银行成功');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
}
