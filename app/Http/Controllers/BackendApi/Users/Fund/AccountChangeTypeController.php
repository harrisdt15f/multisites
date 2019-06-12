<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AccountChangeTypeController extends BackEndApiMainController
{
    protected $eloqM = 'User\Fund\AccountChangeType';

    //帐变类型列表
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['name', 'sign', 'in_out', 'type'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgout(true, $datas);
    }

    //添加帐变类型
    public function add(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'name' => 'required|string',
            'sign' => 'required|string|unique:account_change_types,sign',
            'in_out' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], '400', $validator->errors()->first());
        }
        try {
            $eloqM = new $this->eloqM;
            $eloqM->fill($this->inputs);
            $eloqM->save();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑帐变类型
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:account_change_types,id',
            'name' => 'required|string',
            'sign' => 'required|string',
            'in_out' => 'required|numeric|in:0,1',
            'type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgout(false, [], '101200');
        }
        $checkData = $this->eloqM::where(function ($query) {
            $query->where('sign', $this->inputs['sign'])->where('id', '!=', $this->inputs['id']);
        })->first();
        if (!is_null($checkData)) {
            return $this->msgout(false, [], '101201');
        }
        $editData = $this->inputs;
        unset($editData['id']);
        try {
            $this->editAssignment($pastEloq, $editData);
            $pastEloq->save();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除帐变类型
    public function delete(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:account_change_types,id',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgout(false, [], '101200');
        }
        try {
            $pastEloq->delete();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
