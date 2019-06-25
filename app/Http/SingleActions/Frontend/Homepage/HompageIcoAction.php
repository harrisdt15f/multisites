<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 11:55:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 11:58:58
 */
namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HompageIcoAction
{
    protected $model;

    /**
     * HompageBanner constructor.
     * @param  FrontendAllocatedModel  $frontendAllocatedModel
     */
    public function __construct(FrontendAllocatedModel $frontendAllocatedModel)
    {
        $this->model = $frontendAllocatedModel;
    }

    /**
     * 前台网站头ico
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        if (Cache::has('homepageIco')) {
            $data = Cache::get('homepageIco');
        } else {
            $icoEloq = $this->model::select('value', 'status')->where('en_name', 'frontend.ico')->first();
            if (is_null($icoEloq)) {
                //#######################################################
                return $contll->msgOut(false, [], '400', '前台Ico模块不存在');
            }
            if ($icoEloq->status !== 1) {
                return $contll->msgOut(false, [], '400', $contll->offMsg);
            };
            $data = $icoEloq->value;
            Cache::forever('homepageIco', $data);
        }
        return $contll->msgOut(true, $data);
    }
}
