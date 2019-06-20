<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-20 19:54:27
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-20 20:28:55
 */
namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\Admin\Fund\FrontendSystemBank;
use Illuminate\Http\JsonResponse;

class BankDetailAction
{
    protected $model;

    /**
     * @param  FrontendSystemBank  $frontendSystemBank
     */
    public function __construct(FrontendSystemBank $frontendSystemBank)
    {
        $this->model = $frontendSystemBank;
    }

    /**
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['title', 'code', 'pay_type', 'status'];
        $banksDatas = $contll->generateSearchQuery($this->model, $searchAbleFields);
        return $contll->msgOut(true, $banksDatas);
    }
}
