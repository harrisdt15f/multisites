<?php

namespace App\Http\Controllers\BackendApi\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\FundOperate\BankAddBankRequest;
use App\Http\Requests\Backend\Admin\FundOperate\BankDeleteBankRequest;
use App\Http\Requests\Backend\Admin\FundOperate\BankEditBankRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class BankController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Fund\FrontendSystemBank';

    /**
     * 银行列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['title', 'code', 'pay_type', 'status'];
        $banksDatas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $banksDatas);
    }

    /**
     * 添加银行
     * @param  BankAddBankRequest $request [description]
     * @return JsonResponse
     */
    public function addBank(BankAddBankRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $configure = new $this->eloqM();
            $configure->fill($inputDatas);
            $configure->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 编辑银行
     * @param  BankEditBankRequest $request
     * @return JsonResponse
     */
    public function editBank(BankEditBankRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        $this->editAssignment($pastEloq, $inputDatas);
        try {
            $pastEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除银行
     * @param  BankDeleteBankRequest $request
     * @return JsonResponse
     */
    public function deleteBank(BankDeleteBankRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $this->eloqM::where('id', $inputDatas['id'])->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
