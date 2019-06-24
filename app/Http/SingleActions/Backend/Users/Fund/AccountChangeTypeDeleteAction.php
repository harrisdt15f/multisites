<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 20:20:04
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 20:24:25
 */
namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\User\Fund\AccountChangeType;
use Exception;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeDeleteAction
{
    protected $model;

    /**
     * @param  AccountChangeType  $accountChangeType
     */
    public function __construct(AccountChangeType $accountChangeType)
    {
        $this->model = $accountChangeType;
    }

    /**
     * 删除帐变类型
     * @param  BackEndApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        try {
            $this->model::find($inputDatas['id'])->delete();
            return $contll->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}
