<?php

namespace App\Http\Controllers\BackendApi\Admin\Activity;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosAddRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosDeleteRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosEditRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosSortRequest;
use App\Lib\Common\ImageArrange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
    public function add(ActivityInfosAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        //接收文件信息
        $ImageClass = new ImageArrange();
        $depositPath = $ImageClass->depositPath($this->folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        //进行上传
        $pic = $ImageClass->uploadImg($inputDatas['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        //生成缩略图
        $thumbnail_path = $ImageClass->creatThumbnail($pic['path'], 100, 200, 'sm_');
        //sort
        $maxSort = $this->eloqM::max('sort');
        $sort = is_null($maxSort) ? 1 : $maxSort++;
        $addDatas = $inputDatas;
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
    public function edit(ActivityInfosEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::where('title', $inputDatas['title'])->where('id', '!=', $inputDatas['id'])->first();
        if (!is_null($pastData)) {
            return $this->msgOut(false, [], '100300');
        }
        $editDataEloq = $this->eloqM::find($inputDatas['id']);
        $editData = $inputDatas;
        //如果修改了图片 上传新图片
        if (isset($inputDatas['pic'])) {
            unset($editData['pic']);
            $pastPic = $editDataEloq->pic_path;
            $pastThumbnail = $editDataEloq->thumbnail_path;
            //接收文件信息
            $ImageClass = new ImageArrange();
            $depositPath = $ImageClass->depositPath($this->folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
            //进行上传
            $picdata = $ImageClass->uploadImg($inputDatas['pic'], $depositPath);
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
            if (isset($inputDatas['pic'])) {
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
    public function delete(ActivityInfosDeleteRequest $request)
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::find($inputDatas['id']);
        DB::beginTransaction();
        try {
            $this->eloqM::where('id', $inputDatas['id'])->delete();
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
    public function sort(ActivityInfosSortRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        DB::beginTransaction();
        try {
            //上拉排序
            if ($inputDatas['sort_type'] == 1) {
                $stationaryData = $this->eloqM::find($inputDatas['front_id']);
                $stationaryData->sort = $inputDatas['front_sort'];
                $this->eloqM::where('sort', '>=', $inputDatas['front_sort'])->where('sort', '<', $inputDatas['rearways_sort'])->increment('sort');
                //下拉排序
            } elseif ($inputDatas['sort_type'] == 2) {
                $stationaryData = $this->eloqM::find($inputDatas['rearways_id']);
                $stationaryData->sort = $inputDatas['rearways_sort'];
                $this->eloqM::where('sort', '>', $inputDatas['front_sort'])->where('sort', '<=', $inputDatas['rearways_sort'])->decrement('sort');
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
