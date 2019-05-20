<?php

namespace App\Http\Controllers\BackendApi\Admin\Activity;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use Illuminate\Support\Facades\Validator;

class ActivityInfosController extends BackEndApiMainController
{
    protected $eloqM = 'ActivityInfos';
    //活动列表
    public function detail()
    {
        $searchAbleFields = ['title', 'type', 'status', 'admin_name', 'is_time_interval'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $datas);
    }
    //添加活动
    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'title' => 'required',
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
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($pastData)) {
            return $this->msgOut(false, [], '100300');
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
        $file = $this->inputs['pic'];
        $path = 'uploaded_files/' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id . '/mobile_activity_' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id;
        //进行上传
        $ImageClass = new ImageArrange();
        $pic = $ImageClass->uploadImg($file, $path);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '100302');
        }
        //生成缩略图
        $thumbnail_path = $ImageClass->creatThumbnail($pic['path'], 100, 200, 'sm_');
        $addDatas = $this->inputs;
        unset($addDatas['pic']);
        $addDatas['pic_path'] = '/' . $pic['path'];
        $addDatas['thumbnail_path'] = '/' . $thumbnail_path;
        $addDatas['admin_id'] = $this->partnerAdmin->id;
        $addDatas['admin_name'] = $this->partnerAdmin->name;
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
    //编辑活动
    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
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
        if (is_null($editDataEloq)) {
            return $this->msgOut(false, [], '100301');
        }
        //如果修改了图片 删除原图并且上传新图片
        if (isset($this->inputs['pic']) && !is_null($this->inputs['pic'])) {
            $pic = $this->inputs['pic'];
            unset($this->inputs['pic']);
            $pastpic = $editDataEloq->pic_path;
            $thumbnail_path = $editDataEloq->thumbnail_path;
            //接收文件信息
            $path = 'uploaded_files/' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id . '/mobile_activity_' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id;
            //进行上传
            $ImageClass = new ImageArrange();
            $picdata = $ImageClass->uploadImg($pic, $path);
            if ($picdata['success'] === false) {
                return $this->msgOut(false, [], '100302');
            }
            $editDataEloq->pic_path = '/' . $picdata['path'];
            //生成缩略图
            $editDataEloq->thumbnail_path = '/' . $ImageClass->creatThumbnail($picdata['path'], 100, 200, 'sm_');
        }
        $this->editAssignment($editDataEloq, $this->inputs);
        try {
            $editDataEloq->save();
            if (isset($pic) && !is_null($pic)) {
                //删除原图片
                $ImageClass->deletePic(substr($pastpic, 1));
                $ImageClass->deletePic(substr($thumbnail_path, 1));
            }
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
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (!is_null($pastData)) {
            try {
                $this->eloqM::where('id', $this->inputs['id'])->delete();
                //删除图片
                $ImageClass = new ImageArrange();
                $ImageClass->deletePic(substr($pastData['pic_path'], 1));
                $ImageClass->deletePic(substr($pastData['thumbnail_path'], 1));
                return $this->msgOut(true);
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], 100301);
        }
    }
}
