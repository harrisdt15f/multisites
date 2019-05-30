<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Validator;

class FrontendAllocatedModelController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Frontend\FrontendAllocatedModel';

    public function detail()
    {
        $validator = Validator::make($this->inputs, [
            'type' => 'required|numeric|in:2,3',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $eloqM = new $this->eloqM;
        $allFrontendModel = $eloqM->allFrontendModel($this->inputs['type']);
        return $this->msgOut(true, $allFrontendModel);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'label' => 'required|string',
            'en_name' => 'required|string',
            'pid' => 'required|numeric',
            'type' => 'required|numeric',
            'level' => 'required|numeric|in:1,2,3',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkLabelEloq = $this->eloqM::where('label', $this->inputs['label'])->first();
        if (!is_null($checkLabelEloq)) {
            return $this->msgOut(false, [], '101600');
        }
        $checkEnNamelEloq = $this->eloqM::where('en_name', $this->inputs['en_name'])->first();
        if (!is_null($checkEnNamelEloq)) {
            return $this->msgOut(false, [], '101601');
        }
        if ($this->inputs['pid'] != 0) {
            $checkParentLevel = $this->eloqM::where('id', $this->inputs['pid'])->first();
            if ($checkParentLevel->level === 3) {
                return $this->msgOut(false, [], '101603');
            }
        }
        try {
            $modelEloq = new $this->eloqM;
            $modelEloq->fill($this->inputs);
            $modelEloq->save();
            return $this->msgOut(true);
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
            'label' => 'required|string',
            'en_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101602');
        }
        $checkLabelEloq = $this->eloqM::where(function ($query) {
            $query->where('label', $this->inputs['label'])
                ->where('id', '!=', $this->inputs['id']);
        })->first();
        if (!is_null($checkLabelEloq)) {
            return $this->msgOut(false, [], '101600');
        }
        $checkEnNamelEloq = $this->eloqM::where(function ($query) {
            $query->where('en_name', $this->inputs['en_name'])
                ->where('id', '!=', $this->inputs['id']);
        })->first();
        if (!is_null($checkEnNamelEloq)) {
            return $this->msgOut(false, [], '101601');
        }
        try {
            $pastData->label = $this->inputs['label'];
            $pastData->en_name = $this->inputs['en_name'];
            $pastData->save();
            return $this->msgOut(true);
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
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkidIdEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($checkidIdEloq)) {
            return $this->msgOut(false, [], '101602');
        }
        //检查是否存在下级
        $deleteIds[] = $this->inputs['id'];
        $childs = $this->eloqM::where('pid', $this->inputs['id'])->get()->toArray();
        if (!is_null($childs)) {
            $childsId = array_column($childs, 'id');
            $deleteIds = array_merge($deleteIds, $childsId);
            $grandson = $this->eloqM::whereIn('pid', $childsId)->get()->toArray();
            if (!is_null($grandson)) {
                $grandsonId = array_column($grandson, 'id');
                $deleteIds = array_merge($deleteIds, $grandsonId);
            }
        }
        try {
            $this->eloqM::whereIn('id', $deleteIds)->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
