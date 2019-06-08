<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:38:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-08 14:39:40
 */
namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PopularMethodsController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Homepage\FrontendLotteryFnfBetableList';

    //热门彩票二 玩法列表
    public function detail(): JsonResponse
    {
        $methodEloqs = $this->eloqM::with(['method' => function ($query) {
            $query->select('id', 'lottery_name', 'method_name');
        }])->orderBy('sort', 'asc')->get();
        $datas = [];
        foreach ($methodEloqs as $method) {
            $data = [
                'id' => $method->id,
                'method_id' => $method->method_id,
                'lottery_name' => $method->method->lottery_name,
                'method_name' => $method->method->method_name,
                'sort' => $method->sort,
            ];
            $datas[] = $data;
        }
        return $this->msgOut(true, $datas);
    }

    //热门彩票二 添加热门彩种
    public function add(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'lotteries_id' => 'required|string',
            'method_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //检查数据是否合法
        $checkBool = $this->checkData($this->inputs['lotteries_id'], $this->inputs['method_id']);
        if ($checkBool['success'] === false) {
            return $this->msgOut(false, [], $checkBool['code']);
        }
        //sort
        $maxSort = $this->eloqM::orderBy('sort', 'desc')->first();
        if (is_null($maxSort)) {
            $sort = 1;
        } else {
            $sort = $maxSort->sort + 1;
        }
        $addData = [
            'lotteries_id' => $this->inputs['lotteries_id'],
            'method_id' => $this->inputs['method_id'],
            'sort' => $sort,
        ];
        try {
            $popularLotteriesEloq = new $this->eloqM;
            $popularLotteriesEloq->fill($addData);
            $popularLotteriesEloq->save();
            //清除首页热门玩法缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //热门彩票二 编辑热门玩法
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'lotteries_id' => 'required|string',
            'method_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //检查数据是否合法
        $checkBool = $this->checkData($this->inputs['lotteries_id'], $this->inputs['method_id'], $this->inputs['id']);
        if ($checkBool['success'] === false) {
            return $this->msgOut(false, [], $checkBool['code']);
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastDataEloq)) {
            return $this->msgOut(false, [], '102014');
        }
        try {
            $this->editAssignment($pastDataEloq, $this->inputs);
            $pastDataEloq->save();
            //清除首页热门玩法缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除热门玩法
    public function delete(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastDataEloq)) {
            return $this->msgOut(false, [], '102014');
        }
        $sort = $pastDataEloq->sort;
        DB::beginTransaction();
        try {
            $pastDataEloq->delete();
            //重新排序
            $datas = $this->eloqM::where('sort', '>', $sort)->decrement('sort');
            DB::commit();
            //清除首页热门玩法缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //热门玩法拉动排序
    public function sort(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'front_id' => 'required|numeric|gt:0',
            'rearways_id' => 'required|numeric|gt:0',
            'front_sort' => 'required|numeric|gt:0',
            'rearways_sort' => 'required|numeric|gt:0',
            'sort_type' => 'required|numeric|in:1,2',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastFrontData = $this->eloqM::find($this->inputs['front_id']);
        $pastRearwaysData = $this->eloqM::find($this->inputs['rearways_id']);
        if (is_null($pastFrontData) || is_null($pastRearwaysData)) {
            return $this->msgOut(false, [], '102014');
        }
        DB::beginTransaction();
        try {
            //上拉排序
            if ($this->inputs['sort_type'] == 1) {
                $stationaryData = $this->eloqM::find($this->inputs['front_id']);
                $stationaryData->sort = $this->inputs['front_sort'];
                $this->eloqM::where('sort', '>=', $this->inputs['front_sort'])->where('sort', '<', $this->inputs['rearways_sort'])->increment('sort');
            } elseif ($this->inputs['sort_type'] == 2) {
                //下拉排序
                $stationaryData = $this->eloqM::find($this->inputs['rearways_id']);
                $stationaryData->sort = $this->inputs['rearways_sort'];
                $this->eloqM::where('sort', '>', $this->inputs['front_sort'])->where('sort', '<=', $this->inputs['rearways_sort'])->decrement('sort');
            }
            $stationaryData->save();
            DB::commit();
            //清除首页热门玩法缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //添加热门玩法时选择的玩法列表
    public function methodsList(): JsonResponse
    {
        $lotterys = FrontendLotteryFnfBetableMethod::groupBy('lottery_name')->orderBy('id', 'asc')->pluck('lottery_name')->toArray();
        $data = [];
        foreach ($lotterys as $key => $lottery) {
            $methodIds = FrontendLotteryFnfBetableMethod::where('lottery_name', $lottery)->pluck('id');
            $methods = LotteryMethod::select('lottery_id', 'lottery_name', 'id as method_id', 'method_name')->whereIn('id', $methodIds)->get()->toArray();
            $data[] = $methods;
        }
        return $this->msgOut(true, $data);
    }

    //清除首页热门玩法缓存
    public function deleteCache()
    {
        if (Cache::has('popularMethods')) {
            Cache::forget('popularMethods');
        }
    }

    public function checkData($lotteriesId, $methodId, $id = null)
    {
        //彩种是否已存在
        $checkData = $this->eloqM::where('lotteries_id', $lotteriesId)->where('id', '!=', $id)->exists();
        if ($checkData === true) {
            return ['success' => false, 'code' => '102012'];
        }
        //检查彩种是否合法
        $checkLottery = FrontendLotteryFnfBetableMethod::where('lottery_id', $lotteriesId)->exists();
        if ($checkLottery === false) {
            return ['success' => false, 'code' => '102015'];
        }
        //检查玩法是否合法
        $checkMethod = FrontendLotteryFnfBetableMethod::find($methodId)->exists();
        if ($checkMethod === false) {
            return ['success' => false, 'code' => '102013'];
        }
        return ['success' => true];
    }
}
