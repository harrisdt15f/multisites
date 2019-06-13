<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:38:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-13 15:33:30
 */
namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod;
use App\Models\Game\Lottery\LotteryList;
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
            'lotteries_id' => 'required|exists:lottery_lists,en_name|unique:frontend_lottery_fnf_betable_lists,lotteries_id',
            'method_id' => 'required|exists:frontend_lottery_fnf_betable_methods,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //sort
        $maxSort = $this->eloqM::max('sort');
        $sort = is_null($maxSort) ? 1 : $maxSort++;
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
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //热门彩票二 编辑热门玩法
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|exists:frontend_lottery_fnf_betable_lists,id',
            'lotteries_id' => 'required|exists:lottery_lists,en_name',
            'method_id' => 'required|exists:frontend_lottery_fnf_betable_methods,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        //彩种是否已存在
        $isExistLottery = $this->eloqM::where('lotteries_id', $this->inputs['lotteries_id'])->where('id', '!=', $this->inputs['id'])->exists();
        if ($isExistLottery === true) {
            return $this->msgOut(false, [], '100600');
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
            'id' => 'required|exists:frontend_lottery_fnf_betable_lists,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
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
            'front_id' => 'required|exists:frontend_lottery_fnf_betable_lists,id',
            'rearways_id' => 'required|exists:frontend_lottery_fnf_betable_lists,id',
            'front_sort' => 'required|numeric|gt:0',
            'rearways_sort' => 'required|numeric|gt:0',
            'sort_type' => 'required|numeric|in:1,2',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
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
        $lotteryIds = FrontendLotteryFnfBetableMethod::groupBy('lottery_id')->pluck('lottery_id')->toArray();
        //取出开启状态的彩票
        $lotterys = LotteryList::whereIn('en_name', $lotteryIds)->where('status', 1)->orderBy('id', 'asc')->pluck('cn_name')->toArray();
        $data = [];
        foreach ($lotterys as $lottery) {
            $methodIds = FrontendLotteryFnfBetableMethod::where('lottery_name', $lottery)->pluck('id');
            $methods = LotteryMethod::select('lottery_id', 'lottery_name', 'id as method_id', 'method_name', 'status')->whereIn('id', $methodIds)->get()->toArray();
            $data[$lottery] = $methods;
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

    /**
     * 检查数据是否合法
     * @param  [int] $lotteriesId [彩票id]
     * @param  [int] $methodId    [玩法id]
     * @param  [int] $id          [热门彩票2 id]
     * @return [array]
     */
    public function checkData($lotteriesId, $methodId, $id = null)
    {
        //检查玩法与彩种是否匹配
        if ($isValidMethod->lottery_id !== $lotteriesId) {
            return ['success' => false, 'code' => '102015'];
        }
        return ['success' => true];
    }
}
