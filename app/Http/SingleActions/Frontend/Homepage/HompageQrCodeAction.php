<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 11:29:12
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 11:32:37
 */
namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HompageQrCodeAction
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
     * 首页二维码
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        if (Cache::has('homepageQrCode')) {
            $data = Cache::get('homepageQrCode');
        } else {
            $data = $this->model::select('value', 'status')->where('en_name', 'qr.code')->first()->toArray();
            if ($data['status'] !== 1) {
                return $contll->msgOut(false, [], '400', $contll->offMsg);
            }
            unset($data['status']);
            Cache::forever('homepageQrCode', $data);
        }
        return $contll->msgOut(true, $data);
    }
}
