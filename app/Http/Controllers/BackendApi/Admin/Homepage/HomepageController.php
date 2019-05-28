<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class HomepageController extends BackEndApiMainController
{
    protected $eloqM = 'HomepageModel';

    //导航一列表
    public function navOne(): JsonResponse
    {
        $datas = $this->eloqM::select('id', 'model_name', 'key', 'value', 'status')->where('pid', 1)->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //主题板块列表
    public function pageModel(): JsonResponse
    {
        $datas = $this->eloqM::select('id', 'model_name', 'key', 'value', 'show_num', 'status')->where('pid', 4)->orWhere('key', 'banner')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //编辑首页模块
    public function edit(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'status' => 'numeric|in:0,1',
            'value' => 'string',
            'show_num' => 'numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101900');
        }
        if (array_key_exists('status', $this->inputs)) {
            $pastData->status = $this->inputs['status'];
        }
        if (array_key_exists('value', $this->inputs)) {
            if ($pastData->is_edit_value === 1) {
                $pastData->value = $this->inputs['value'];
            } else {
                return $this->msgOut(false, [], '101901');
            }
        }
        if (array_key_exists('show_num', $this->inputs)) {
            if ($pastData->is_edit_show_num === 1) {
                $pastData->show_num = $this->inputs['show_num'];
            } else {
                return $this->msgOut(false, [], '101902');
            }
        }
        try {
            $pastData->save();
            //如果修改了展示状态  清楚首页展示model的缓存
            if (isset($this->inputs['status'])) {
                if (Cache::has('showModel')) {
                    Cache::forget('showModel');
                }
            }
            //删除前台首页缓存
            $this->deleteCache($pastData->key);
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //修改首页模块下的图片
    public function uploadPic(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'key' => 'required|string',
            'pic' => 'required|image',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('key', $this->inputs['key'])->first();
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101903');
        }
        //上传图片
        $imgClass = new ImageArrange();
        $depositPath = $imgClass->depositPath($this->inputs['key'], $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imgClass->uploadImg($this->inputs['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        $pastLogoPath = $pastData->value;
        try {
            $pastData->value = '/' . $pic['path'];
            $pastData->save();
            //删除原图
            if (!is_null($pastLogoPath)) {
                $imgClass->deletePic(substr($pastLogoPath, 1));
            }
            //删除前台首页缓存
            $this->deleteCache($pastData->key);
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除前台首页缓存
    public function deleteCache($key)
    {
        $homepageCache = [
            'qr.code' => 'homepageQrCode',
            'customer.service' => 11,
            'notice' => 11,
            'activity' => 'homepageActivity',
            'logo' => 'homepageLogo',
        ];
        if (isset($homepageCache[$key])) {
            if (Cache::has($homepageCache[$key])) {
                Cache::forget($homepageCache[$key]);
            }
        }
    }
}
