<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 11:24:17
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-26 20:38:53
 */
namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableList;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HompagePopularMethodsAction
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
     * 热门彩票二-玩法
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        if (Cache::has('popularMethods')) {
            $datas = Cache::get('popularMethods');
        } else {
            $lotteriesEloq = $this->model::select('show_num', 'status')->where('en_name', 'popularLotteries.two')->first();
            if ($lotteriesEloq->status !== 1) {
                return $contll->msgOut(false, [], '400', $contll->offMsg);
            }
            $methodsEloq = FrontendLotteryFnfBetableList::orderBy('sort', 'asc')->limit($lotteriesEloq->show_num)->with('method')->get();
            $datas = [];
            foreach ($methodsEloq as $method) {
                $data = [
                    'lotteries_id' => $method->lotteries_id,
                    'method_id' => $method->method_id,
                    'lottery_name' => $method->method->lottery_name,
                    'method_name' => $method->method->method_name,
                ];
                $datas[] = $data;
            }
            Cache::forever('popularMethods', $datas);
        }
        return $contll->msgOut(true, $datas);
    }

}
