<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Common\Image;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\models\LotteriesModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PopularLotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'PopularLotteries';

    public function detailOne()
    {
        $datas = $this->eloqM::where('type', 1)->orderBy('sort', 'asc')->get();
        return $this->msgOut(true, $datas);
    }

    public function detailTwo()
    {
        $datas = $this->eloqM::where('type', 2)->orderBy('sort', 'asc')->get();
        return $this->msgOut(true, $datas);
    }

    public function add()
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
            $imgClass = new Image();
            $depositPath = $imgClass->depositPath('popular_lotteries', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
            $pic = $imgClass->uploadImg($this->inputs['pic'], $depositPath);
            if ($pic['success'] === false) {
                return $this->msgOut(false, [], '102002');
            }
            $addData['pic_path'] = '/' . $pic['path'];
        }
        try {
            $popularLotteriesEloq = new $this->eloqM;
            $popularLotteriesEloq->fill($addData);
            $popularLotteriesEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'pic' => 'required|image',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '102003');
        }
        if ($pastData->type === 2) {
            return $this->msgOut(false, [], '102004');
        }
        $pastPic = $pastData->pic_path;
        $imgClass = new Image();
        $depositPath = $imgClass->depositPath('popular_lotteries', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imgClass->uploadImg($this->inputs['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '102002');
        }
        $pastData->pic_path = '/' . $pic['path'];
        try {
            $pastData->save();
            $imgClass->deletePic(substr($pastPic, 1));
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function delete()
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
                $imgClass = new Image();
                $imgClass->deletePic(substr($pastData['pic_path'], 1));
            }
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //排序
    public function lotteriesSort()
    {
        $validator = Validator::make($this->inputs, [
            'front_id' => 'required|numeric|gt:0',
            'rearways_id' => 'required|numeric|gt:0',
            'front_sort' => 'required|numeric|gt:0',
            'rearways_sort' => 'required|numeric|gt:0',
            'sort_type' => 'required|in:1,2',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastFrontData = $this->eloqM::find($this->inputs['front_id']);
        $pastRearwaysData = $this->eloqM::find($this->inputs['rearways_id']);
        if ($pastFrontData->type !== $pastRearwaysData->type) {
            return $this->msgOut(false, [], '102007');
        }
        $type = $pastFrontData->type;
        DB::beginTransaction();
        try {
            //上拉排序
            if ($this->inputs['sort_type'] == 1) {
                $frontData = $this->eloqM::find($this->inputs['front_id']);
                if (!isset($frontData)) {
                    return $this->msgOut(false, [], '102006');
                }
                $frontData->sort = $this->inputs['front_sort'];
                $this->eloqM::where(function ($query) use ($type) {
                    $query->where('type', $type)
                        ->where('sort', '>=', $this->inputs['front_sort'])
                        ->where('sort', '<', $this->inputs['rearways_sort']);
                })->increment('sort');
            } elseif ($this->inputs['sort_type'] == 2) {
                //下拉排序
                $frontData = $this->eloqM::find($this->inputs['rearways_id']);
                if (!isset($frontData)) {
                    return $this->msgOut(false, [], '102006');
                }
                $frontData->sort = $this->inputs['rearways_sort'];
                $this->eloqM::where(function ($query) use ($type) {
                    $query->where('type', $type)
                        ->where('sort', '>', $this->inputs['front_sort'])
                        ->where('sort', '<=', $this->inputs['rearways_sort']);
                })->decrement('sort');
            }
            $frontData->save();
            DB::commit();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //彩种列表
    public function lotteriesList()
    {
        $lotteries = LotteriesModel::select('cn_name', 'en_name')->get();
        return $this->msgOut(true, $lotteries);
    }
}
