<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:28:04
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 15:51:17
 */
namespace App\Http\Controllers\BackendApi\DeveloperUsage\MethodLevel;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\MethodLevel\MethodLevelAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\MethodLevel\MethodLevelDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\MethodLevel\MethodLevelEditRequest;
use App\Http\SingleActions\Backend\DeveloperUsage\MethodLevel\MethodLevelAddAction;
use App\Http\SingleActions\Backend\DeveloperUsage\MethodLevel\MethodLevelDeleteAction;
use App\Http\SingleActions\Backend\DeveloperUsage\MethodLevel\MethodLevelDetailAction;
use App\Http\SingleActions\Backend\DeveloperUsage\MethodLevel\MethodLevelEditAction;
use App\Lib\Common\CacheRelated;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class MethodLevelController extends BackEndApiMainController
{
    /**
     * 玩法等级管理列表
     * @param   MethodLevelDetailAction $action
     * @return  JsonResponse
     */
    public function detail(MethodLevelDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加玩法等级
     * @param   MethodLevelAddRequest $request
     * @param   MethodLevelAddAction  $action
     * @return  JsonResponse
     */
    public function add(MethodLevelAddRequest $request, MethodLevelAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑玩法等级
     * @param   MethodLevelEditRequest $request
     * @param   MethodLevelEditAction  $action
     * @return  JsonResponse
     */
    public function edit(MethodLevelEditRequest $request, MethodLevelEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除玩法等级
     * @param   MethodLevelDeleteRequest $request
     * @param   MethodLevelDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(MethodLevelDeleteRequest $request, MethodLevelDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除玩法等级列表缓存
     * @return void
     */
    public function deleteCache(): void
    {
        $key = 'methodLeveDetail';
        $cacheRelated = new CacheRelated();
        $cacheRelated->delete($key);
    }
}
