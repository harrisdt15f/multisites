<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelDetailRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelEditRequest;
use App\Models\DeveloperUsage\Frontend\FrontendAppRoute;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Support\Facades\DB;

class FrontendAllocatedModelController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Frontend\FrontendAllocatedModel';

    //前端模块列表
    public function detail(FrontendAllocatedModelDetailRequest $request)
    {
        $inputDatas = $request->validated();
        $eloqM = new $this->eloqM;
        $allFrontendModel = $eloqM->allFrontendModel($inputDatas['type']);
        return $this->msgOut(true, $allFrontendModel);
    }

    //添加前端模块
    public function add(FrontendAllocatedModelAddRequest $request)
    {
        $inputDatas = $request->validated();
        if ($inputDatas['pid'] != 0) {
            $checkParentLevel = $this->eloqM::where('id', $inputDatas['pid'])->first();
            if ($checkParentLevel->level === 3) {
                return $this->msgOut(false, [], '101603');
            }
        }
        try {
            $modelEloq = new $this->eloqM;
            $modelEloq->fill($inputDatas);
            $modelEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑前端模块
    public function edit(FrontendAllocatedModelEditRequest $request)
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::find($inputDatas['id']);
        $checkLabelEloq = $this->eloqM::where('label', $inputDatas['label'])->where('id', '!=', $inputDatas['id'])->first();
        if (!is_null($checkLabelEloq)) {
            return $this->msgOut(false, [], '101600');
        }
        $checkEnNamelEloq = $this->eloqM::where('en_name', $inputDatas['en_name'])->where('id', '!=', $inputDatas['id'])->first();
        if (!is_null($checkEnNamelEloq)) {
            return $this->msgOut(false, [], '101601');
        }
        try {
            $pastData->label = $inputDatas['label'];
            $pastData->en_name = $inputDatas['en_name'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除前端模块
    public function delete(FrontendAllocatedModelDeleteRequest $request)
    {
        $inputDatas = $request->validated();
        $modelEloq = $this->eloqM::find($inputDatas['id']);
        //检查是否存在下级
        $deleteIds[] = $inputDatas['id'];
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
