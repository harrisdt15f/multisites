<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Game\Lottery\LotteriesModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PopularLotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Homepage\PopularLotteries';

    //热门彩种一 列表
    public function detailOne(): JsonResponse
    {
        $datas = $this->eloqM::select('id', 'lotteries_id', 'pic_path', 'sort')->with(['lotteries' => function ($query) {
            $query->select('id', 'cn_name');
        }])->where('type', 1)->orderBy('sort', 'asc')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //热门彩种二 列表
    public function detailTwo(): JsonResponse
    {
        $datas = $this->eloqM::select('id', 'lotteries_id', 'sort')->with(['lotteries' => function ($query) {
            $query->select('id', 'cn_name');
        }])->where('type', 2)->orderBy('sort', 'asc')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //添加热门彩种
    public function add(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'lotteries_id' => 'required|numeric',
            'type' => 'required|numeric|in:1,2',
            'pic' => 'image',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkLotteries = $this->eloqM::where(function ($query) {
            $query->where('lotteries_id', $this->inputs['lotteries_id'])
                ->where('type', $this->inputs['type']);
        })->first();
        if (!is_null($checkLotteries)) {
            return $this->msgOut(false, [], '102000');
        }
        if ($this->inputs['type'] == 1 && !array_key_exists('pic', $this->inputs)) {
            return $this->msgOut(false, [], '102001');
        }
        //sort
        $maxSort = $this->eloqM::where('type', $this->inputs['type'])->orderBy('sort', 'desc')->first();
        if (is_null($maxSort)) {
            $sort = 1;
        } else {
            $sort = $maxSort->sort + 1;
        }
        $addData = [
            'lotteries_id' => $this->inputs['lotteries_id'],
            'type' => $this->inputs['type'],
            'sort' => $sort,
        ];
        if ($this->inputs['type'] == 1) {
            //上传图片
            $imgClass = new ImageArrange();
            $depositPath = $imgClass->depositPath('popular_lotteries', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
            $pic = $imgClass->uploadImg($this->inputs['pic'], $depositPath);
            if ($pic['success'] === false) {
                return $this->msgOut(false, [], '400', $pic['msg']);
            }
            $addData['pic_path'] = '/' . $pic['path'];
        }
        try {
            $popularLotteriesEloq = new $this->eloqM;
            $popularLotteriesEloq->fill($addData);
            $popularLotteriesEloq->save();
            //清除首页热门彩种缓存
            $this->deleteCache($popularLotteriesEloq->type);
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑热门彩种
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'pic' => 'image',
            'lotteries_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '102003');
        }
        //检查彩种是否存在
        $checkLotteries = LotteriesModel::find($this->inputs['lotteries_id']);
        if (is_null($checkLotteries)) {
            return $this->msgOut(false, [], '102011');
        }
        //热门类型一 并且修改了图片的操作
        if ($pastData->type === 1) {
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
        }
        //检查该热门类型是否存在重复彩种
        $checkData = $this->eloqM::where('lotteries_id', $this->inputs['lotteries_id'])->where('type', $pastData->type)->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($checkData)) {
            return $this->msgOut(false, [], '102010');
        }
        $pastData->lotteries_id = $this->inputs['lotteries_id'];
        try {
            $pastData->save();
            if (isset($pastPic)) {
                $imgClass->deletePic(substr($pastPic, 1));
            }
            //清除首页热门彩种缓存
            $this->deleteCache($pastData->type);
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

    //删除热门彩种
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
            return $this->msgOut(false, [], '102005');
        }
        $pastData = $pastDataEloq;
        DB::beginTransaction();
        try {
            $pastDataEloq->delete();
            //重新排序
            $datas = $this->eloqM::where(function ($query) use ($pastData) {
                $query->where('sort', '>', $pastData->sort)
                    ->where('type', $pastData->type);
            })->decrement('sort');
            DB::commit();
            //热门彩种1 删除图片
            if ($pastData->type === 1) {
                $imgClass = new ImageArrange();
                $imgClass->deletePic(substr($pastData['pic_path'], 1));
            }
            //清除首页热门彩种缓存
            $this->deleteCache($pastData->type);
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //热门彩票拉动排序
    public function lotteriesSort(): JsonResponse
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
        if ($pastFrontData->type !== $pastRearwaysData->type) {
            return $this->msgOut(false, [], '102007');
        }
        $type = $pastFrontData->type;
        DB::beginTransaction();
        try {
            //上拉排序
            if ($this->inputs['sort_type'] == 1) {
                $stationaryData = $this->eloqM::find($this->inputs['front_id']);
                $stationaryData->sort = $this->inputs['front_sort'];
                $this->eloqM::where(function ($query) use ($type) {
                    $query->where('type', $type)
                        ->where('sort', '>=', $this->inputs['front_sort'])
                        ->where('sort', '<', $this->inputs['rearways_sort']);
                })->increment('sort');
            } elseif ($this->inputs['sort_type'] == 2) {
                //下拉排序
                $stationaryData = $this->eloqM::find($this->inputs['rearways_id']);
                $stationaryData->sort = $this->inputs['rearways_sort'];
                $this->eloqM::where(function ($query) use ($type) {
                    $query->where('type', $type)
                        ->where('sort', '>', $this->inputs['front_sort'])
                        ->where('sort', '<=', $this->inputs['rearways_sort']);
                })->decrement('sort');
            }
            $stationaryData->save();
            DB::commit();
            //清除首页热门彩种缓存
            $this->deleteCache($stationaryData->type);
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
        $lotteries = LotteriesModel::select('id', 'cn_name', 'en_name')->get();
        return $this->msgOut(true, $lotteries);
    }

    //
    public function deleteCache($type)
    {
        if ($type == 1) {
            if (Cache::has('popularLotteriesOne')) {
                Cache::forget('popularLotteriesOne');
            }
        } elseif ($type == 2) {
            if (Cache::has('popularLotteriesTwo')) {
                Cache::forget('popularLotteriesTwo');
            }
        }
    }
}
