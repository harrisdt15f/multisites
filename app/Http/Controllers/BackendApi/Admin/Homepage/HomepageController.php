<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\HomepageEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageUploadIcoRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageUploadPicRequest;
use App\Lib\Common\ImageArrange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HomepageController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Frontend\FrontendAllocatedModel';

    //导航一列表
    public function navOne(): JsonResponse
    {
        $frontendModelEloq = new $this->eloqM;
        $navEloq = $frontendModelEloq->getModel('nav.one');
        $datas = $this->eloqM::select('id', 'label', 'en_name', 'value', 'show_num', 'status')->where('pid', $navEloq->id)->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //主题板块列表
    public function pageModel(): JsonResponse
    {
        $frontendModelEloq = new $this->eloqM;
        $pageEloq = $frontendModelEloq->getModel('page.model');
        $datas = $this->eloqM::select('id', 'label', 'en_name', 'value', 'show_num', 'status')->where('pid', $pageEloq->id)->orWhere('en_name', 'banner')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //编辑首页模块
    public function edit(HomepageEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::find($inputDatas['id']);
        if (isset($inputDatas['status'])) {
            $pastData->status = $inputDatas['status'];
        }
        if (isset($inputDatas['value'])) {
            $pastData->value = $inputDatas['value'];
        }
        if (isset($inputDatas['show_num'])) {
            $pastData->show_num = $inputDatas['show_num'];
        }
        try {
            $pastData->save();
            //如果修改了展示状态  清楚首页展示model的缓存
            if (isset($inputDatas['status'])) {
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
    public function uploadPic(HomepageUploadPicRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::where('en_name', $inputDatas['en_name'])->first();
        //上传图片
        $imgClass = new ImageArrange();
        $depositPath = $imgClass->depositPath($inputDatas['en_name'], $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imgClass->uploadImg($inputDatas['pic'], $depositPath);
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
            $this->deleteCache($pastData->en_name);
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //上传前台网站头ico
    public function uploadIco(HomepageUploadIcoRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::where('en_name', 'frontend.ico')->first();
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101900');
        }
        //上传ico
        $imageClass = new ImageArrange();
        $folderName = 'frontend';
        $depositPath = $imageClass->depositPath($folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name) . '/ico';
        $ico = $imageClass->uploadImg($inputDatas['ico'], $depositPath);
        $pastIco = $pastData->value;
        try {
            $pastData->value = '/' . $ico['path'];
            $pastData->save();
            //删除前台首页缓存
            $this->deleteCache($pastData->en_name);
            //删除原图
            if (!is_null($pastIco)) {
                $imageClass->deletePic(substr($pastIco, 1));
            }
            return $this->msgOut(true);
        } catch (Exception $e) {
            //删除上传成功的图片
            $imageClass->deletePic($ico['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除前台首页缓存
    public function deleteCache($key): void
    {
        $homepageCache = [
            'qr.code' => 'homepageQrCode',
            'notice' => 'homepageNotice',
            'activity' => 'homepageActivity',
            'logo' => 'homepageLogo',
            'frontend.ico' => 'homepageIco',
        ];
        if (isset($homepageCache[$key])) {
            if (Cache::has($homepageCache[$key])) {
                Cache::forget($homepageCache[$key]);
            }
        }
    }
}
