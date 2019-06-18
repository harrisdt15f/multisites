<?php

namespace App\Http\Controllers\BackendApi\Admin\Activity;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosAddRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosDeleteRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosEditRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosSortRequest;
use App\Lib\Common\ImageArrange;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ActivityInfosController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Activity\FrontendActivityContent';
    protected $folderName = 'mobile_activity'; //活动图片存放的文件夹名称

    /**
     * 活动列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['title', 'type', 'status', 'admin_name', 'is_time_interval'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $datas);
    }

    /**
     * 添加活动
     * @param ActivityInfosAddRequest $request [description]
     * @return  JsonResponse
     */
    public function add(ActivityInfosAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        //接收文件信息
        $imageObj = new ImageArrange();
        $depositPath = $imageObj->depositPath($this->folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        //进行上传
        $pic = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        //生成缩略图
        $thumbnail_path = $imageObj->creatThumbnail($pic['path'], 100, 200);
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
        } catch (Exception $e) {
            $imageObj->deletePic($pic['path']);
            $imageObj->deletePic($thumbnail_path);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 编辑活动
     * @param  ActivityInfosEditRequest $request
     * @return JsonResponse
     */
    public function edit(ActivityInfosEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $issetTitle = $this->eloqM::where('title', $inputDatas['title'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($issetTitle === true) {
            return $this->msgOut(false, [], '100300');
        }
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        $editData = $inputDatas;
        //如果修改了图片 上传新图片
        if (isset($inputDatas['pic'])) {
            unset($editData['pic']);
            $pastPic = $pastDataEloq->pic_path;
            $pastThumbnail = $pastDataEloq->thumbnail_path;
            //接收文件信息
            $imageObj = new ImageArrange();
            $depositPath = $imageObj->depositPath($this->folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
            //进行上传
            $picdata = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
            if ($picdata['success'] === false) {
                return $this->msgOut(false, [], '400', $picdata['msg']);
            }
            $pastDataEloq->pic_path = '/' . $picdata['path'];
            //生成缩略图
            $pastDataEloq->thumbnail_path = '/' . $imageObj->creatThumbnail($picdata['path'], 100, 200);
        }
        $this->editAssignment($pastDataEloq, $editData);
        try {
            $pastDataEloq->save();
            if (isset($inputDatas['pic'])) {
                //删除原图片
                $imageObj->deletePic(substr($pastPic, 1));
                $imageObj->deletePic(substr($pastThumbnail, 1));
            }
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除活动
     * @param  ActivityInfosDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(ActivityInfosDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        DB::beginTransaction();
        try {
            $this->eloqM::where('id', $inputDatas['id'])->delete();
            //排序
            $this->eloqM::where('sort', '>', $pastDataEloq->sort)->decrement('sort');
            DB::commit();
            //删除图片
            $imageObj = new ImageArrange();
            $imageObj->deletePic(substr($pastDataEloq->pic_path, 1));
            $imageObj->deletePic(substr($pastDataEloq->thumbnail_path, 1));
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
