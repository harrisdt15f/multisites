<?php

namespace App\Http\Controllers\BackendApi;

use Illuminate\Support\Facades\Validator;

class BankController extends BackEndApiMainController
{
    protected $eloqM = 'Banks';
    public function detail()
    {
        $searchAbleFields = ['title', 'code', 'pay_type', 'status'];
        $banksDatas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $banksDatas);
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
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $addDatas = $this->inputs;
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgOut(true, [], '200');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
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
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $editDataEloq = $this->eloqM::find($this->inputs['id']);
        if (empty($editDataEloq)) {
            return $this->msgOut(false, [], '100600');
        }
        $this->editAssignment($editDataEloq, $this->inputs);
        try {
            $editDataEloq->save();
            return $this->msgOut(true, [], '200');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
    public function deleteBank()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        try {
            $this->eloqM::where('id', $this->inputs['id'])->delete();
            return $this->msgOut(true, [], '200');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
