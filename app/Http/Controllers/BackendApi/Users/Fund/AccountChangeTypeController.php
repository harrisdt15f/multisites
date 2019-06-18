<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeAddRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeDeleteRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeEditRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeController extends BackEndApiMainController
{
    protected $eloqM = 'User\Fund\AccountChangeType';

    /**
     * 帐变类型列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['name', 'sign', 'in_out', 'type'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgout(true, $datas);
    }

    /**
     * 添加帐变类型
     * @param AccountChangeTypeAddRequest $request
     * @return JsonResponse
     */
    public function add(AccountChangeTypeAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $eloqM = new $this->eloqM;
            $eloqM->fill($inputDatas);
            $eloqM->save();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 编辑帐变类型
     * @param  AccountChangeTypeEditRequest $request
     * @return JsonResponse
     */
    public function edit(AccountChangeTypeEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $isExistSign = $this->eloqM::where('sign', $inputDatas['sign'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($isExistSign === true) {
            return $this->msgout(false, [], '101200');
        }
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        try {
            $this->editAssignment($pastEloq, $inputDatas);
            $pastEloq->save();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除帐变类型
     * @param  AccountChangeTypeDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(AccountChangeTypeDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $this->eloqM::find($inputDatas['id'])->delete();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
