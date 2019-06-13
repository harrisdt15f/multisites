<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:28:04
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-13 11:38:16
 */
namespace App\Http\Controllers\BackendApi\DeveloperUsage\MethodLevel;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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
    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'method_id' => 'required|string|exists:lottery_methods,id',
            'level' => 'required|numeric|gt:0|lt:11',
            'position' => 'required|string',
            'count' => 'required|numeric',
            'prize' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //检查玩法等级
        $checkMethodLevel = $this->eloqM::where('method_id', $this->inputs['method_id'])->where('level', $this->inputs['level'])->first();
        if (!is_null($checkMethodLevel)) {
            return $this->msgOut(false, [], '102201');
        }
        try {
            $methodLevelEloq = new $this->eloqM;
            $methodLevelEloq->fill($this->inputs);
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
    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:lottery_methods_ways_levels,id',
            'level' => 'required|numeric|gt:0|lt:11',
            'position' => 'required|string',
            'count' => 'required|numeric',
            'prize' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        //检查玩法等级
        $checkMethodLevel = $this->eloqM::where('method_id', $pastDataEloq->method_id)->where('level', $this->inputs['level'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($checkMethodLevel)) {
            return $this->msgOut(false, [], '102202');
        }
        try {
            $this->editAssignment($pastDataEloq, $this->inputs);
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
    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:lottery_methods_ways_levels,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        try {
            $pastDataEloq->delete();
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
