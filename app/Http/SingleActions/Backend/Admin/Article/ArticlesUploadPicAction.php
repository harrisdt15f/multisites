<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-20 16:15:40
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-26 17:03:53
 */
namespace App\Http\SingleActions\Backend\Admin\Article;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Activity\BackendAdminMessageArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ArticlesUploadPicAction
{
    protected $model;

    /**
     * @param  BackendAdminMessageArticle  $backendAdminMessageArticle
     */
    public function __construct(BackendAdminMessageArticle $backendAdminMessageArticle)
    {
        $this->model = $backendAdminMessageArticle;
    }

    /**
     * 图片上传
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        $imageObj = new ImageArrange();
        $file = $inputDatas['pic'];
        $depositPath = $imageObj->depositPath($contll->folderName, $contll->currentPlatformEloq->platform_id, $contll->currentPlatformEloq->platform_name);
        //进行上传
        $pic = $imageObj->uploadImg($file, $depositPath);
        if ($pic['success'] === false) {
            return $contll->msgOut(false, [], '400', $pic['msg']);
        }
        $hourToStore = 24 * 2;
        $expiresAt = Carbon::now()->addHours($hourToStore);
        if (Cache::has('CachePic')) {
            $cachePic = Cache::get('CachePic');
            $cachePic[$pic['name']] = $pic;
        } else {
            $cachePic[$pic['name']] = $pic;
        }
        Cache::put('CachePic', $cachePic, $expiresAt);
        return $contll->msgOut(true, $pic);
    }
}
