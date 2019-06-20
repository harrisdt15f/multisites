<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-20 11:51:13
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-20 20:13:37
 */
namespace App\Http\SingleActions\Backend\Admin\Activity;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Activity\FrontendActivityContent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ActivityInfosAddAction
{
    protected $model;

    /**
     * @param  FrontendActivityContent  $frontendActivityContent
     */
    public function __construct(FrontendActivityContent $frontendActivityContent)
    {
        $this->model = $frontendActivityContent;
    }

    /**
     * @param  BackEndApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        if (!Cache::has('currentPlatformEloq')) {
            return $contll->msgOut(false, [], '100301');
        }
        $currentPlatformEloq = Cache::get('currentPlatformEloq');
        if (!Cache::has('partnerAdmin')) {
            return $contll->msgOut(false, [], '100302');
        }
        $partnerAdmin = Cache::get('partnerAdmin');
        //接收文件信息
        $imageObj = new ImageArrange();
        $depositPath = $imageObj->depositPath($contll->folderName, $currentPlatformEloq->platform_id, $currentPlatformEloq->platform_name);
        //进行上传
        $pic = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $contll->msgOut(false, [], '400', $pic['msg']);
        }
        //生成缩略图
        $thumbnailPath = $imageObj->creatThumbnail($pic['path'], 100, 200);
        //sort
        $maxSort = $this->model::select('sort')->max('sort');
        $sort = ++$maxSort;
        $addDatas = $inputDatas;
        unset($addDatas['pic']);
        $addDatas['sort'] = $sort;
        $addDatas['pic_path'] = '/' . $pic['path'];
        $addDatas['thumbnail_path'] = '/' . $thumbnailPath;
        $addDatas['admin_id'] = $partnerAdmin->id;
        $addDatas['admin_name'] = $partnerAdmin->name;
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            //删除前台首页缓存
            $contll->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imageObj->deletePic($pic['path']);
            $imageObj->deletePic($thumbnailPath);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
