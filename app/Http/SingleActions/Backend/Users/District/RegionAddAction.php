<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 19:33:15
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 19:40:47
 */
namespace App\Http\SingleActions\Backend\Users\District;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class RegionAddAction
{
    protected $model;

    /**
     * @param  UsersRegion  $usersRegion
     */
    public function __construct(UsersRegion $usersRegion)
    {
        $this->model = $usersRegion;
    }

    /**
     * 添加行政区
     * @param  BackEndApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        $isExist = $this->model::where('region_id', '=', $inputDatas['region_id'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($isExist === true) {
            return $contll->msgOut(false, [], '101001');
        }
        $editDataEloq = $this->model::find($inputDatas['id']);
        $editDataEloq->region_id = $inputDatas['region_id'];
        $editDataEloq->region_name = $inputDatas['region_name'];
        try {
            $editDataEloq->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}
