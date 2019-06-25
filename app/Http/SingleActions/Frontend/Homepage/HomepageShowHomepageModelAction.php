<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 11:02:09
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 11:08:04
 */
namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HomepageShowHomepageModelAction
{
    protected $model;

    /**
     * @param  FrontendAllocatedModel  $frontendAllocatedModel
     */
    public function __construct(FrontendAllocatedModel $frontendAllocatedModel)
    {
        $this->model = $frontendAllocatedModel;
    }

    /**
     * 需要展示的前台模块
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        if (Cache::has('showModel')) {
            $data = Cache::get('showModel');
        } else {
            $homepageModel = $this->model::select('en_name', 'status')->where('is_homepage_display', 1)->get();
            $data = [];
            foreach ($homepageModel as $value) {
                $data[$value->en_name] = $value->status;
            }
            Cache::forever('showModel', $data);
        }
        return $contll->msgOut(true, $data);
    }
}
