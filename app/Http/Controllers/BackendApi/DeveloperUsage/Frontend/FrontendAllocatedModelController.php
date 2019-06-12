<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAppRoute;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FrontendAllocatedModelController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Frontend\FrontendAllocatedModel';

    //前端模块列表
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

    //添加前端模块
    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'label' => 'required|string|unique:frontend_allocated_models,label',
            'en_name' => 'required|string|unique:frontend_allocated_models,en_name',
            'pid' => 'required|numeric',
            'type' => 'required|numeric',
            'level' => 'required|numeric|in:1,2,3',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
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

    //编辑前端模块
    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_allocated_models,id',
            'label' => 'required|string',
            'en_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
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

    //删除前端模块
    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_allocated_models,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $modelEloq = $this->eloqM::find($this->inputs['id']);
        //检查是否存在下级
        $deleteIds[] = $this->inputs['id'];
        $childs = $modelEloq->childs->pluck('id')->toArray();
        if (!is_null($childs)) {
            $deleteIds = array_merge($deleteIds, $childs);
            $grandson = $this->eloqM::whereIn('pid', $childs)->pluck('id')->toArray();
            if (!is_null($grandson)) {
                $deleteIds = array_merge($deleteIds, $grandson);
            }
        }
        DB::beginTransaction();
        try {
            $this->eloqM::whereIn('id', $deleteIds)->delete();
            //删除绑定该模块的路由
            $issetWebRoute = FrontendWebRoute::whereIn('frontend_model_id', $deleteIds)->exists();
            if ($issetWebRoute === true) {
                FrontendWebRoute::whereIn('frontend_model_id', $deleteIds)->delete();
            }
            $issetAppRoute = FrontendAppRoute::whereIn('frontend_model_id', $deleteIds)->get();
            if ($issetAppRoute === true) {
                FrontendAppRoute::whereIn('frontend_model_id', $deleteIds)->delete();
            }
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
