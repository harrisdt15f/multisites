<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesSortRequest;
use App\Lib\Common\ImageArrange;
use App\Models\Game\Lottery\LotteryList;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PopularLotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Homepage\FrontendLotteryRedirectBetList';

    /**
     * 热门彩票列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $lotterieEloqs = $this->eloqM::select('id', 'lotteries_id', 'pic_path', 'sort')->with(['lotteries' => function ($query) {
            $query->select('id', 'cn_name');
        }])->orderBy('sort', 'asc')->get();
        $datas = [];
        foreach ($lotterieEloqs as $lotterie) {
            $data = [
                'id' => $lotterie->id,
                'pic_path' => $lotterie->pic_path,
                'cn_name' => $lotterie->lotteries->cn_name,
                'sort' => $lotterie->sort,
            ];
            $datas[] = $data;
        }
        return $this->msgOut(true, $datas);
    }

    /**
     * 添加热门彩票一
     * @param PopularLotteriesAddRequest $request
     * @return JsonResponse
     */
    public function add(PopularLotteriesAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        //sort
        $maxSort = $this->eloqM::select('sort')->max('sort');
        $sort = ++$maxSort;
        //上传图片
        $imgClass = new ImageArrange();
        $depositPath = $imgClass->depositPath('popular_lotteries', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imgClass->uploadImg($inputDatas['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        $addData = [
            'lotteries_id' => $inputDatas['lotteries_id'],
            'sort' => $sort,
            'pic_path' => '/' . $pic['path'],
        ];
        try {
            $popularLotteriesEloq = new $this->eloqM;
            $popularLotteriesEloq->fill($addData);
            $popularLotteriesEloq->save();
            //清除首页热门彩票缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 编辑热门彩票
     * @param  PopularLotteriesEditRequest $request
     * @return JsonResponse
     */
    public function edit(PopularLotteriesEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        //检查该热门类型是否存在重复彩票
        $checkData = $this->eloqM::where('lotteries_id', $inputDatas['lotteries_id'])->where('id', '!=', $inputDatas['id'])->first();
        if ($checkData !== null) {
            return $this->msgOut(false, [], '102000');
        }
        //修改了图片的操作
        if (isset($inputDatas['pic'])) {
            $pastPic = $pastDataEloq->pic_path;
            $imgClass = new ImageArrange();
            $depositPath = $imgClass->depositPath('popular_lotteries', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
            $pic = $imgClass->uploadImg($inputDatas['pic'], $depositPath);
            if ($pic['success'] === false) {
                return $this->msgOut(false, [], '400', $pic['msg']);
            }
            $pastDataEloq->pic_path = '/' . $pic['path'];
        }
        try {
            $pastDataEloq->lotteries_id = $inputDatas['lotteries_id'];
            $pastDataEloq->save();
            if (isset($pastPic)) {
                $imgClass->deletePic(substr($pastPic, 1));
            }
            //清除首页热门彩票缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            if (isset($pic)) {
                $imgClass->deletePic($pic['path']);
            }
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除热门彩票
     * @param  PopularLotteriesDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(PopularLotteriesDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        $pastData = $pastDataEloq;
        DB::beginTransaction();
        try {
            $pastDataEloq->delete();
            //重新排序
            $datas = $this->eloqM::where('sort', '>', $pastData->sort)->decrement('sort');
            DB::commit();
            //删除图片
            $imgClass = new ImageArrange();
            $imgClass->deletePic(substr($pastData->pic_path, 1));
            //清除首页热门彩票缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 热门彩票拉动排序
     * @param  PopularLotteriesSortRequest $request
     * @return JsonResponse
     */
    public function sort(PopularLotteriesSortRequest $request): JsonResponse
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
            //清除首页热门彩票缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 选择的彩种列表
     * @return JsonResponse
     */
    public function lotteriesList(): JsonResponse
    {
        $lotteries = LotteryList::select('id', 'cn_name', 'en_name')->get();
        return $this->msgOut(true, $lotteries);
    }

    /**
     * 删除前台首页热门彩票缓存
     * @return void
     */
    public function deleteCache(): void
    {
        if (Cache::has('popularLotteries')) {
            Cache::forget('popularLotteries');
        }
    }
}
