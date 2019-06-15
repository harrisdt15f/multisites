<?php

namespace App\Http\Controllers\BackendApi\Admin\Advertisement;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Advertisement\AdvertisementTypeEditRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class AdvertisementTypeController extends BackEndApiMainController
{
    protected $eloqM = 'Advertisement\FrontendSystemAdsType';

    //广告列表
    public function detail(): JsonResponse
    {
        $datas = $this->eloqM::select('id', 'name', 'type', 'status', 'ext_type', 'l_size', 'w_size', 'size')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    /**
     * 编辑广告类型
     * @param  AdvertisementTypeEditRequest $request
     * @return JsonResponse
     */
    public function edit(AdvertisementTypeEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $editData = $this->eloqM::find($inputDatas['id']);
        $this->editAssignment($editData, $inputDatas);
        try {
            $editData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
