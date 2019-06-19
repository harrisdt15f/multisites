<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 11:53 AM
 */

namespace App\Http\SingleActions;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Controllers\WebControllers\HomeController;
use App\Models\Admin\Homepage\FrontendPageBanner;
use Illuminate\Support\Facades\Cache;


class HompageBanner
{

    public function __construct()
    {

    }

    /**
     * @param  FrontendApiMainController  $contll
     * @return mixed
     */
    public function execute(FrontendApiMainController $contll)
    {
        if (Cache::has('homepageBanner')) {
            $datas = Cache::get('homepageBanner');
        } else {
            $status = $contll->eloqM::select('status')->where('en_name', 'banner')->first();
            if ($status->status !== 1) {
                return $contll->msgOut(false, [], '400', $contll->offMsg);
            }
            $datas = FrontendPageBanner::select('id', 'title', 'pic_path', 'content', 'type', 'redirect_url',
                'activity_id')
                ->with([
                    'activity' => static function ($query) {
                        $query->select('id', 'redirect_url');
                    }
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