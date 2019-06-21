<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerSortRequest;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageActivityListAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerAddAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerDeleteAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerDetailAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerEditAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerPicStandardAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerSortAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageReplaceImageAction;
use App\Lib\Common\CacheRelated;
use Illuminate\Http\JsonResponse;

class HomepageBannerController extends BackEndApiMainController
{
    public $folderName = 'Homepagec_Rotation_chart';

    /**
     * 首页轮播图列表
     * @param    HomepageBannerDetailAction $action
     * @return   JsonResponse
     */
    public function detail(HomepageBannerDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加首页轮播图
     * @param   HomepageBannerAddRequest $request
     * @param   HomepageBannerAddAction  $action
     * @return  JsonResponse
     */
    public function add(HomepageBannerAddRequest $request, HomepageBannerAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑首页轮播图
     * @param   HomepageBannerEditRequest  $request
     * @param   HomepageBannerEditAction   $action
     * @return  JsonResponse
     */
    public function edit(HomepageBannerEditRequest $request, HomepageBannerEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除首页轮播图
     * @param   HomepageBannerDeleteRequest $request
     * @param   HomepageBannerDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(HomepageBannerDeleteRequest $request, HomepageBannerDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 首页轮播图排序
     * @param   HomepageBannerSortRequest $request
     * @param   HomepageBannerSortAction  $action
     * @return  JsonResponse
     */
    public function sort(HomepageBannerSortRequest $request, HomepageBannerSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 操作轮播图时获取的活动列表
     * @param   HomepageActivityListAction $action
     * @return  JsonResponse
     */
    public function activityList(HomepageActivityListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 上传图片的规格
     * @param   HomepageBannerPicStandardAction $action
     * @return  JsonResponse
     */
    public function picStandard(HomepageBannerPicStandardAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 清除首页banner缓存
     * @return void
     */
    public function deleteCache(): void
    {
        $cacheRelated = new CacheRelated();
        $cacheRelated->delete('homepageBanner');
    }
}
