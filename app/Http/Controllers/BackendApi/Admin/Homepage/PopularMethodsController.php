<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:38:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 19:26:17
 */
namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsSortRequest;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PopularMethodsController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Homepage\FrontendLotteryFnfBetableList';

    //热门彩票二 玩法列表
    public function detail(): JsonResponse
    {
        $methodEloqs = $this->eloqM::with('method')->orderBy('sort', 'asc')->get();
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
    public function add(PopularMethodsAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        //sort
        $maxSort = $this->eloqM::max('sort');
        $sort = is_null($maxSort) ? 1 : $maxSort++;
        $addData = [
            'lotteries_id' => $inputDatas['lotteries_id'],
            'method_id' => $inputDatas['method_id'],
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
    public function edit(PopularMethodsEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        //彩种是否已存在
        $isExistLottery = $this->eloqM::where('lotteries_id', $inputDatas['lotteries_id'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($isExistLottery === true) {
            return $this->msgOut(false, [], '100600');
        }
        try {
            $this->editAssignment($pastDataEloq, $inputDatas);
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
    public function delete(PopularMethodsDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
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
    public function sort(PopularMethodsSortRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        DB::beginTransaction();
        try {
            //上拉排序
            if ($inputDatas['sort_type'] == 1) {
                $stationaryData = $this->eloqM::find($inputDatas['front_id']);
                $stationaryData->sort = $inputDatas['front_sort'];
                $this->eloqM::where('sort', '>=', $inputDatas['front_sort'])->where('sort', '<', $inputDatas['rearways_sort'])->increment('sort');
            } elseif ($inputDatas['sort_type'] == 2) {
                //下拉排序
                $stationaryData = $this->eloqM::find($inputDatas['rearways_id']);
                $stationaryData->sort = $inputDatas['rearways_sort'];
                $this->eloqM::where('sort', '>', $inputDatas['front_sort'])->where('sort', '<=', $inputDatas['rearways_sort'])->decrement('sort');
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
}
