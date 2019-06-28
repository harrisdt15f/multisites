<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 11:53 AM
 */

namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\Homepage\FrontendPageBanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HompageBannerAction
{
    protected $model;

    /**
     * HompageBanner constructor.
     * @param  FrontendPageBanner  $frontendPageBanner
     */
    public function __construct(FrontendPageBanner $frontendPageBanner)
    {
        $this->model = $frontendPageBanner;
    }

    /**
     * 首页轮播图列表
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        if (Cache::has('homepageBanner')) {
            $datas = Cache::get('homepageBanner');
        } else {
            $status = $contll->model::select('status')->where('en_name', 'banner')->first();
            if ($status->status !== 1) {
                return $contll->msgOut(false, [], '400', $contll->offMsg);
            }
            $datas = $this->model::select('id', 'title', 'pic_path', 'content', 'type', 'redirect_url',
                'activity_id')
                ->with([
                    'activity' => static function ($query) {
                        $query->select('id', 'redirect_url');
                    },
                ])
                ->where('status', 1)->orderBy('sort', 'asc')->get()->toArray();
            foreach ($datas as $key => $data) {
                if ($data['type'] === 2) {
                    $datas[$key]['redirect_url'] = $data['activity']['redirect_url'];
                }
                unset($datas[$key]['activity'], $datas[$key]['activity_id']);
            }
            Cache::forever('homepageBanner', $datas);
        }
        return $contll->msgOut(true, $datas);
    }

}
