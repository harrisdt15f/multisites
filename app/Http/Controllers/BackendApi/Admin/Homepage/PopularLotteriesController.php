<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PopularLotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Homepage\FrontendLotteryRedirectBetList';

    //热门彩票列表
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

    //添加热门彩票
    public function add(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'lotteries_id' => 'required|numeric|unique:frontend_lottery_redirect_bet_lists,lotteries_id|exists:lottery_lists,id',
            'pic' => 'required|image',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkLotterie = $this->eloqM::where('lotteries_id', $this->inputs['lotteries_id'])->first();
        //sort
        $maxSort = $this->eloqM::max('sort');
        $sort = is_null($maxSort) ? 1 : $maxSort++;
        //上传图片
        $imgClass = new ImageArrange();
        $depositPath = $imgClass->depositPath('popular_lotteries', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imgClass->uploadImg($this->inputs['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        $addData = [
            'lotteries_id' => $this->inputs['lotteries_id'],
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

    //编辑热门彩票
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_lottery_redirect_bet_lists,id',
            'pic' => 'image',
            'lotteries_id' => 'required|numeric|exists:lottery_lists,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        //检查该热门类型是否存在重复彩票
        $checkData = $this->eloqM::where('lotteries_id', $this->inputs['lotteries_id'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($checkData)) {
            return $this->msgOut(false, [], '102010');
        }
        //修改了图片的操作
        if (isset($this->inputs['pic'])) {
            $pastPic = $pastData->pic_path;
            $imgClass = new ImageArrange();
            $depositPath = $imgClass->depositPath('popular_lotteries', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
            $pic = $imgClass->uploadImg($this->inputs['pic'], $depositPath);
            if ($pic['success'] === false) {
                return $this->msgOut(false, [], '400', $pic['msg']);
            }
            $pastData->pic_path = '/' . $pic['path'];
        }
        try {
            $pastData->lotteries_id = $this->inputs['lotteries_id'];
            $pastData->save();
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

    //删除热门彩票
    public function delete(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_lottery_redirect_bet_lists,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        $pastData = $pastDataEloq;
        DB::beginTransaction();
        try {
            $pastDataEloq->delete();
            //重新排序
            $datas = $this->eloqM::where('sort', '>', $pastData->sort)->decrement('sort');
            DB::commit();
            //删除图片
            $imgClass = new ImageArrange();
            $imgClass->deletePic(substr($pastData['pic_path'], 1));
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

    //热门彩票拉动排序
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
            return $this->msgOut(false, [], '102008');
        }
        DB::beginTransaction();
        try {
            //上拉排序
            if ($this->inputs['sort_type'] == 1) {
                $stationaryData = $this->eloqM::find($this->inputs['front_id']);
                $stationaryData->sort = $this->inputs['front_sort'];
                $this->eloqM::where(function ($query) {
                    $query->where('sort', '>=', $this->inputs['front_sort'])
                        ->where('sort', '<', $this->inputs['rearways_sort']);
                })->increment('sort');
            } elseif ($this->inputs['sort_type'] == 2) {
                //下拉排序
                $stationaryData = $this->eloqM::find($this->inputs['rearways_id']);
                $stationaryData->sort = $this->inputs['rearways_sort'];
                $this->eloqM::where(function ($query) {
                    $query->where('sort', '>', $this->inputs['front_sort'])
                        ->where('sort', '<=', $this->inputs['rearways_sort']);
                })->decrement('sort');
            }
            $stationaryData->save();
            DB::commit();
            //清除首页热门彩票缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //选择的  彩种列表
    public function lotteriesList(): JsonResponse
    {
        $lotteries = LotteryList::select('id', 'cn_name', 'en_name')->get();
        return $this->msgOut(true, $lotteries);
    }

    //删除 前台首页热门彩票缓存
    public function deleteCache()
    {
        if (Cache::has('popularLotteries')) {
            Cache::forget('popularLotteries');
        }
    }
}
