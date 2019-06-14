<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:28:04
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 17:56:27
 */
namespace App\Http\Controllers\BackendApi\DeveloperUsage\MethodLevel;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\MethodLevel\MethodLevelAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\MethodLevel\MethodLevelDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\MethodLevel\MethodLevelEditRequest;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Support\Facades\Cache;

class MethodLevelController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel';

    //玩法等级管理列表
    public function detail()
    {
        if (Cache::has('methodLeveDetail')) {
            $data = Cache::get('methodLeveDetail');
        } else {
            $methodLevelEloq = new $this->eloqM;
            $data = $methodLevelEloq->methodLevelDetail();
            Cache::forever('methodLeveDetail', $data);
        }
        return $this->msgOut(true, $data);
    }

    //添加玩法等级
    public function add(MethodLevelAddRequest $request)
    {
        $inputDatas = $request->validated();
        //检查玩法等级
        $checkMethodLevel = $this->eloqM::where('method_id', $inputDatas['method_id'])->where('level', $inputDatas['level'])->first();
        if (!is_null($checkMethodLevel)) {
            return $this->msgOut(false, [], '102201');
        }
        try {
            $methodLevelEloq = new $this->eloqM;
            $methodLevelEloq->fill($inputDatas);
            $methodLevelEloq->save();
            //删除玩法等级列表缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑玩法等级
    public function edit(MethodLevelEditRequest $request)
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        //检查玩法等级
        $checkMethodLevel = $this->eloqM::where('method_id', $pastDataEloq->method_id)->where('level', $inputDatas['level'])->where('id', '!=', $inputDatas['id'])->first();
        if (!is_null($checkMethodLevel)) {
            return $this->msgOut(false, [], '102200');
        }
        try {
            $this->editAssignment($pastDataEloq, $inputDatas);
            $pastDataEloq->save();
            //删除玩法等级列表缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除玩法等级
    public function delete(MethodLevelDeleteRequest $request)
    {
        $inputDatas = $request->validated();
        try {
            $this->eloqM::where('id', $inputDatas['id'])->delete();
            //删除玩法等级列表缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除玩法等级列表缓存
    public function deleteCache()
    {
        if (Cache::has('methodLeveDetail')) {
            Cache::forget('methodLeveDetail');
        }
    }
}
