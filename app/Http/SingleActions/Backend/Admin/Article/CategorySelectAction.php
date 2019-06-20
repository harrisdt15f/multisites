<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-20 18:16:25
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-20 20:13:21
 */
namespace App\Http\SingleActions\Backend\Admin\Article;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\Admin\Activity\FrontendInfoCategorie;
use Illuminate\Http\JsonResponse;

class CategorySelectAction
{
    protected $model;

    /**
     * @param  FrontendInfoCategorie  $frontendInfoCategorie
     */
    public function __construct(FrontendInfoCategorie $frontendInfoCategorie)
    {
        $this->model = $frontendInfoCategorie;
    }

    /**
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $datas = $this->model::select('id', 'title')->get()->toArray();
        return $contll->msgOut(true, $datas);
    }
}
