<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 19:53:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 20:24:19
 */
namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUserAccountType;
use Exception;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeAddAction
{
    protected $model;

    /**
     * @param  FrontendUserAccountType  $frontendUserAccountType
     */
    public function __construct(FrontendUserAccountType $frontendUserAccountType)
    {
        $this->model = $frontendUserAccountType;
    }

    /**
     * 添加帐变类型
     * @param  BackEndApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        try {
            $eloqM = new $this->model;
            $eloqM->fill($inputDatas);
            $eloqM->save();
            return $contll->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}
