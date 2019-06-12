<?php

namespace App\Http\Controllers\BackendApi\Admin\Activity;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityInfosController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Activity\FrontendActivityContent';
    protected $folderName = 'mobile_activity'; //活动图片存放的文件夹名称

    //活动列表
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['title', 'type', 'status', 'admin_name', 'is_time_interval'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $datas);
    }

    //添加活动
    public function add(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'title' => 'required|unique:frontend_activity_contents,title',
            'content' => 'required',
            'pic' => 'required|image|mimes:jpeg,png,jpg',
            'start_time' => 'date_format:Y-m-d H:i:s',
            'end_time' => 'date_format:Y-m-d H:i:s',
            'status' => 'required',
            'redirect_url' => 'required',
            'is_time_interval' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //活动是否永久 与 开始结束时间 的处理
        if ($this->inputs['is_time_interval'] == 1) {
            if (!array_key_exists('start_time', $this->inputs) || !array_key_exists('end_time', $this->inputs)) {
                return $this->msgOut(false, [], '100303');
            }
        } elseif ($this->inputs['is_time_interval'] == 0) {
            if (array_key_exists('start_time', $this->inputs)) {
                unset($this->inputs['start_time']);
            }
            if (array_key_exists('end_time', $this->inputs)) {
                unset($this->inputs['end_time']);
            }
        }
        //接收文件信息
        $ImageClass = new ImageArrange();
        $depositPath = $ImageClass->depositPath($this->folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        //进行上传
        $pic = $ImageClass->uploadImg($this->inputs['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        //生成缩略图
        $thumbnail_path = $ImageClass->creatThumbnail($pic['path'], 100, 200, 'sm_');
        //sort
        $sortdata = $this->eloqM::orderBy('sort', 'desc')->first();
        if (is_null($sortdata)) {
            $sort = 1;
        } else {
            $sort = $sortdata->sort + 1;
        }
        $addDatas = $this->inputs;
        unset($addDatas['pic']);
        $addDatas['sort'] = $sort;
        $addDatas['pic_path'] = '/' . $pic['path'];
        $addDatas['thumbnail_path'] = '/' . $thumbnail_path;
        $addDatas['admin_id'] = $this->partnerAdmin->id;
        $addDatas['admin_name'] = $this->partnerAdmin->name;
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $ImageClass->deletePic($pic['path']);
            $ImageClass->deletePic($thumbnail_path);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑活动
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_activity_contents,id',
            'title' => 'required',
            'content' => 'required',
            'pic' => 'image|mimes:jpeg,png,jpg',
            'start_time' => 'date_format:Y-m-d H:i:s',
            'end_time' => 'date_format:Y-m-d H:i:s',
            'status' => 'required',
            'redirect_url' => 'required',
            'is_time_interval' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //活动是否永久 与 开始结束时间 的处理
        if ($this->inputs['is_time_interval'] == 1) {
            if (!array_key_exists('start_time', $this->inputs) || !array_key_exists('end_time', $this->inputs)) {
                return $this->msgOut(false, [], '100303');
            }
        } elseif ($this->inputs['is_time_interval'] == 0) {
            $this->inputs['start_time'] = null;
            $this->inputs['end_time'] = null;
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($pastData)) {
            return $this->msgOut(false, [], '100300');
        }
        $editDataEloq = $this->eloqM::find($this->inputs['id']);
        $editData = $this->inputs;
        //如果修改了图片 上传新图片
        if (isset($this->inputs['pic'])) {
            //
            unset($editData['pic']);
            $pastPic = $editDataEloq->pic_path;
            $pastThumbnail = $editDataEloq->thumbnail_path;
            //接收文件信息
            $ImageClass = new ImageArrange();
            $depositPath = $ImageClass->depositPath($this->folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
            //进行上传
            $picdata = $ImageClass->uploadImg($this->inputs['pic'], $depositPath);
            if ($picdata['success'] === false) {
                return $this->msgOut(false, [], '400', $picdata['msg']);
            }
            $editDataEloq->pic_path = '/' . $picdata['path'];
            //生成缩略图
            $editDataEloq->thumbnail_path = '/' . $ImageClass->creatThumbnail($picdata['path'], 100, 200, 'sm_');
        }
        $this->editAssignment($editDataEloq, $editData);
        try {
            $editDataEloq->save();
            if (isset($this->inputs['pic'])) {
                //删除原图片
                $ImageClass->deletePic(substr($pastPic, 1));
                $ImageClass->deletePic(substr($pastThumbnail, 1));
            }
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除活动
    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_activity_contents,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        DB::beginTransaction();
        try {
            $this->eloqM::where('id', $this->inputs['id'])->delete();
            //排序
            $this->eloqM::where('sort', '>', $pastData['sort'])->decrement('sort');
            DB::commit();
            //删除图片
            $ImageClass = new ImageArrange();
            $ImageClass->deletePic(substr($pastData['pic_path'], 1));
            $ImageClass->deletePic(substr($pastData['thumbnail_path'], 1));
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }

    }

    //活动排序
    public function sort(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'front_id' => 'required|exists:frontend_activity_contents,id',
            'rearways_id' => 'required|exists:frontend_activity_contents,id',
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
                //下拉排序
            } elseif ($this->inputs['sort_type'] == 2) {
                $stationaryData = $this->eloqM::find($this->inputs['rearways_id']);
                $stationaryData->sort = $this->inputs['rearways_sort'];
                $this->eloqM::where('sort', '>', $this->inputs['front_sort'])->where('sort', '<=', $this->inputs['rearways_sort'])->decrement('sort');
            }
            $stationaryData->save();
            DB::commit();
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除前台首页缓存
    public function deleteCache()
    {
        if (Cache::has('homepageActivity')) {
            Cache::forget('homepageActivity');
        }
    }
}
