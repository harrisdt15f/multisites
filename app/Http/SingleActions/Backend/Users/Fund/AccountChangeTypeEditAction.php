<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 20:15:47
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 20:24:30
 */
namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUserAccountType;
use Exception;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeEditAction
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
     * 编辑帐变类型
     * @param  BackEndApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        $isExistSign = $this->model::where('sign', $inputDatas['sign'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($isExistSign === true) {
            return $contll->msgout(false, [], '101200');
        }
        try {
            $pastEloq = $this->model::find($inputDatas['id']);
            $contll->editAssignment($pastEloq, $inputDatas);
            $pastEloq->save();
            return $contll->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}
