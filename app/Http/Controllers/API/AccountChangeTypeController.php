<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class AccountChangeTypeController extends ApiMainController
{
    protected $eloqM = 'AccountChangeType';

    public function detail()
    {
        $searchAbleFields = ['name', 'sign', 'in_out', 'type'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgout(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'name' => 'required|string',
            'sign' => 'required|string',
            'in_out' => 'required|numeric|in:0,1',
            'type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], '400', $validator->errors()->first());
        }
        $checkData = $this->eloqM::where('sign', $this->inputs['sign'])->first();
        if (!is_null($checkData)) {
            return $this->msgout(false, [], '101201');
        }
        $eloqM = new $this->eloqM;
        try {
            $eloqM->fill($this->inputs);
            $eloqM->save();
            return $this->msgout(true, '200');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
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
            return $this->msgout(true, '200');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
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
            return $this->msgout(true, [], '200');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
