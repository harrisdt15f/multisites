<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-19 14:36:57
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-20 20:13:46
 */
namespace App\Http\SingleActions\Backend\Admin\Activity;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\Admin\Activity\FrontendActivityContent;
use Illuminate\Http\JsonResponse;

class ActivityInfosDetailAction
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
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['title', 'type', 'status', 'admin_name', 'is_time_interval'];
        $datas = $contll->generateSearchQuery($this->model, $searchAbleFields);
        return $contll->msgOut(true, $datas);
    }

}
