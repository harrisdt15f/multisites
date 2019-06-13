<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Activity\FrontendActivityContent;
use App\Models\Advertisement\FrontendSystemAdsType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomepageRotationChartController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Homepage\FrontendPageBanner';
    protected $folderName = 'Homepagec_Rotation_chart';
    //首页轮播图列表
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['title', 'type'];
        $data = $this->eloqM::orderBy('sort', 'asc')->get()->toArray();
        return $this->msgOut(true, $data);
    }

    //添加首页轮播图
    public function add(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'title' => 'required|string|unique:frontend_page_banners,title',
            'content' => 'required|string',
            'pic' => 'required|image',
            'type' => 'required|numeric|in:1,2',
            'redirect_url' => 'string|required_if:type,1',
            'activity_id' => 'numeric|required_if:type,2|exists:frontend_activity_contents,id',
            'status' => 'required|numeric|in:0,1',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //上传图片
        $imageClass = new ImageArrange();
        $folderName = $this->folderName;
        $depositPath = $imageClass->depositPath($folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imageClass->uploadImg($this->inputs['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        //生成缩略图
        $thumbnail = $imageClass->creatThumbnail($pic['path'], 100, 200, 'sm_');
        $addData = $this->inputs;
        unset($addData['pic']);
        $addData['pic_path'] = '/' . $pic['path'];
        $addData['thumbnail_path'] = '/' . $thumbnail;
        //sort
        $maxSort = $this->eloqM::max('sort');
        $addData['sort'] = is_null($maxSort) ? 1 : $maxSort++;
        try {
            $rotationChartEloq = new $this->eloqM;
            $rotationChartEloq->fill($addData);
            $rotationChartEloq->save();
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑首页轮播图
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_page_banners,id',
            'title' => 'required|string',
            'content' => 'required|string',
            'pic' => 'image',
            'redirect_url' => 'string|required_if:type,1',
            'activity_id' => 'numeric|required_if:type,2|exists:frontend_activity_contents,id',
            'status' => 'required|numeric|in:0,1',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        $checkTitle = $this->eloqM::where(function ($query) {
            $query->where('title', $this->inputs['title'])
                ->where('id', '!=', $this->inputs['id']);
        })->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '101800');
        }
        $editData = $this->inputs;
        unset($editData['id']);
        unset($editData['pic']);
        //如果要修改图片  删除原图  上传新图
        if (isset($this->inputs['pic'])) {
            $imageClass = new ImageArrange();
            $picData = $this->replaceImage($pastData['pic_path'], $pastData['thumbnail_path'], $this->inputs['pic'], $imageClass);
            if ($picData['success'] === false) {
                return $this->msgOut(false, [], $picData['code']);
            }
            //上传缩略图
            $thumbnail = $imageClass->creatThumbnail($picData['path'], 100, 200, 'sm_');
            $editData['pic_path'] = '/' . $picData['path'];
            $editData['thumbnail_path'] = '/' . $thumbnail;
        }
        try {
            $this->editAssignment($pastData, $editData);
            $pastData->save();
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除首页轮播图
    public function delete(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_page_banners,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastDataEloq = $this->eloqM::find($this->inputs['id']);
        $pastData = $pastDataEloq;
        DB::beginTransaction();
        try {
            $imageClass = new ImageArrange();
            $pastDataEloq->delete();
            //往后的sort重新排序
            $this->eloqM::where('sort', '>', $pastData->sort)->decrement('sort');
            DB::commit();
            $deleteStatus = $imageClass->deletePic(substr($pastData['pic_path'], 1));
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //首页轮播图排序
    public function sort(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'front_id' => 'required|numeric|exists:frontend_page_banners,id',
            'rearways_id' => 'required|numeric|exists:frontend_page_banners,id',
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
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //操作轮播图时获取的活动列表
    public function activityList()
    {
        $activityList = FrontendActivityContent::select('id', 'title')->where('status', 1)->get();
        return $this->msgOut(true, $activityList);
    }

    /**
     * 修改轮播图时   替换图片
     * @param     $pastImg     原图路径
     * @param     $thumbnail     缩略图路径
     * @param     $newImg     新图文件
     * @param     $imageClass     图片类
     */
    public function replaceImage($pastImg, $thumbnail, $newImg, $imageClass): array
    {
        $imageClass->deletePic(substr($pastImg, 1));
        $imageClass->deletePic(substr($thumbnail, 1));
        $folderName = $this->folderName;
        $depositPath = $imageClass->depositPath($folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $picData = $imageClass->uploadImg($newImg, $depositPath);
        if ($picData['success'] === true) {
            return $picData;
        } else {
            return ['success' => false, 'code' => '101801'];
        }
    }

    //上传图片的规格
    public function picStandard(): JsonResponse
    {
        $standard = FrontendSystemAdsType::select('l_size', 'w_size', 'size')->where('type', 1)->first();
        return $this->msgOut(true, $standard);
    }

    //清除首页banner缓存
    public function deleteCache(): void
    {
        if (Cache::has('homepageBanner')) {
            Cache::forget('homepageBanner');
        }
    }
}
