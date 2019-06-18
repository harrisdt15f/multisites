<?php

namespace App\Http\Controllers\BackendApi\Users\District;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\District\RegionAddRequest;
use App\Http\Requests\Backend\Users\District\RegionEditRequest;
use App\Http\Requests\Backend\Users\District\RegionGetTownRequest;
use App\Http\Requests\Backend\Users\District\RegionSearchTownRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class RegionController extends BackEndApiMainController
{
    protected $eloqM = 'User\UsersRegion';

    /**
     * 获取 省-市-县 列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $datas = $this->eloqM::whereIn('region_level', [1, 2, 3])->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    /**
     * 获取 镇(街道) 列表
     * @param  RegionGetTownRequest $request
     * @return JsonResponse
     */
    public function getTown(RegionGetTownRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $check = $this->eloqM::where(['region_level' => $inputDatas['region_level'], 'region_id' => $inputDatas['region_parent_id']])->first();
        if (is_null($check)) {
            return $this->msgOut(false, [], '101000');
        }
        $datas = $this->eloqM::where(['region_level' => 4, 'region_parent_id' => $inputDatas['region_parent_id']])->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    /**
     * 模糊搜索 镇(街道)
     * @param  RegionSearchTownRequest $request
     * @return JsonResponse
     */
    public function searchTown(RegionSearchTownRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $datas = $this->eloqM::select('a.*', 'b.region_name as country_name', 'c.region_name as city_name', 'd.region_name as province_name')
            ->from('users_regions as a')
            ->leftJoin('users_regions as b', 'a.region_parent_id', '=', 'b.region_id')
            ->leftJoin('users_regions as c', 'b.region_parent_id', '=', 'c.region_id')
            ->leftJoin('users_regions as d', 'c.region_parent_id', '=', 'd.region_id')
            ->where([['a.region_name', 'like', '%' . $inputDatas['search_name'] . '%'], ['a.region_level', 4]])
            ->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    /**
     * 添加行政区
     * @param RegionAddRequest $request [description]
     * @return JsonResponse
     */
    public function add(RegionAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $checkData = $this->eloqM::where(['region_parent_id' => $inputDatas['region_parent_id'], 'region_name' => $inputDatas['region_name']])->orwhere('region_id', $inputDatas['region_id'])->first();
        if ($checkData !== null) {
            return $this->msgOut(false, [], '101001');
        }
        try {
            $configure = new $this->eloqM();
            $configure->fill($inputDatas);
            $configure->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
    //编辑行政区
    public function edit(RegionEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::where('region_id', '=', $inputDatas['region_id'])->where('id', '!=', $inputDatas['id'])->first();
        if ($pastData !== null) {
            return $this->msgOut(false, [], '101001');
        }
        $editDataEloq = $this->eloqM::find($inputDatas['id']);
        $editDataEloq->region_id = $inputDatas['region_id'];
        $editDataEloq->region_name = $inputDatas['region_name'];
        try {
            $editDataEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
