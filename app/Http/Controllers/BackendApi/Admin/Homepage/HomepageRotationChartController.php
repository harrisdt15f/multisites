<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Common\Image;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Validator;

class HomepageRotationChartController extends BackEndApiMainController
{
    protected $eloqM = 'HomepageRotationChart';

    public function detail()
    {
        $searchAbleFields = ['title', 'type'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'title' => 'required|string',
            'content' => 'required|string',
            'pic' => 'required|image',
            'type' => 'required|numeric|in:1,2',
            'redirect_url' => 'string',
            'activity_id' => 'numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkTitle = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '101800');
        }
        if ($this->inputs['type'] == 1) {
            if (!array_key_exists('redirect_url', $this->inputs)) {
                return $this->msgOut(false, [], '101801');
            }
        } elseif ($this->inputs['type'] == 2) {
            if (!array_key_exists('activity_id', $this->inputs)) {
                return $this->msgOut(false, [], '101802');
            }
        }
        try {
            $path = 'uploaded_files/' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id . '/Homepagec_Rotation_chart_' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id;
            $ImageClass = new Image();
            $pic = $ImageClass->uploadImg($this->inputs['pic'], $path);
            if ($pic['success'] === false) {
                return $this->msgOut(false, [], '101803');
            }
            $thumbnail = $ImageClass->creatThumbnail($pic['path'], 100, 200, 'sm_');
            $addData = $this->inputs;
            unset($addData['pic']);
            $addData['pic_path'] = '/' . $pic['path'];
            $addData['thumbnail_path'] = '/' . $thumbnail;
            $rotationChartEloq = new $this->eloqM;
            $rotationChartEloq->fill($addData);
            $rotationChartEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'title' => 'required|string',
            'content' => 'required|string',
            'pic' => 'image',
            'type' => 'required|numeric|in:1,2',
            'redirect_url' => 'string',
            'activity_id' => 'numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101804');
        }
        $checkTitle = $this->eloqM::where(function ($query) {
            $query->where('title', $this->inputs['title'])
                ->where('id', '!=', $this->inputs['id']);
        })->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '101800');
        }
        if ($this->inputs['type'] == 1) {
            if (!array_key_exists('redirect_url', $this->inputs)) {
                return $this->msgOut(false, [], '101801');
            }
        } elseif ($this->inputs['type'] == 2) {
            if (!array_key_exists('activity_id', $this->inputs)) {
                return $this->msgOut(false, [], '101802');
            }
        }
        //如果上传了新图片   就替换原图
        $ImageClass = new Image();
        if (array_key_exists('pic', $this->inputs)) {
            $picData = $this->replaceImage($pastData['pic_path'], $pastData['thumbnail_path'], $this->inputs['pic'], $ImageClass);
        }
        if ($picData['success'] === false) {
            return $this->msgOut(false, [], $picData['code']);
        }
        //上传缩略图
        $thumbnail = $ImageClass->creatThumbnail($picData['path'], 100, 200, 'sm_');
        try {
            $editData = $this->inputs;
            unset($editData['id']);
            unset($editData['pic']);
            $editData['pic_path'] = '/' . $picData['path'];
            $editData['thumbnail_path'] = '/' . $thumbnail;
            $this->editAssignment($pastData, $editData);
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
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
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101806');
        }
        try {
            $ImageClass = new Image();
            $deleteStatus = $ImageClass->deletePic(substr($pastData['pic_path'], 1));
            if ($deleteStatus === false) {
                return $this->msgOut(false, [], '101805');
            }
            $pastData->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
    /**
     * 修改轮播图时   替换图片
     * @param     $pastImg     原图路径
     * @param     $thumbnail     缩略图路径
     * @param     $newImg     新图文件
     * @param     $ImageClass     图片类
     */
    public function replaceImage($pastImg, $thumbnail, $newImg, $ImageClass)
    {
        $deleteStatus = $ImageClass->deletePic(substr($pastImg, 1));
        if ($deleteStatus === false) {
            return ['success' => false, 'code' => '101805'];
        }
        $ImageClass->deletePic(substr($thumbnail, 1));
        $folderName = 'Homepagec_Rotation_chart';
        $depositPath = $ImageClass->depositPath($folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $picData = $ImageClass->uploadImg($newImg, $depositPath);
        if ($picData['success'] === true) {
            return $picData;
        } else {
            return ['success' => false, 'code' => '101803'];
        }
    }
}
