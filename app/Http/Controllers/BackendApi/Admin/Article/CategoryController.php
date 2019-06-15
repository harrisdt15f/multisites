<?php

namespace App\Http\Controllers\BackendApi\Admin\Article;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Http\JsonResponse;

class CategoryController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Activity\FrontendInfoCategorie';

    /**
     * 分类管理列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $datas = $this->eloqM::from('frontend_info_categories as self')->leftJoin('frontend_info_categories as secondary', 'self.parent', '=', 'secondary.id')->select('self.*', 'secondary.title as parent_title')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    /**
     * 操作文章时获取的分类列表
     * @return JsonResponse
     */
    public function select(): JsonResponse
    {
        $datas = $this->eloqM::select('id', 'title')->get()->toArray();
        return $this->msgOut(true, $datas);
    }
}
