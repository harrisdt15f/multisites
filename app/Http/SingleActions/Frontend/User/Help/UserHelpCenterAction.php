<?php
/**
 * @Author: Fish
 * @Date:   2019/7/3 18:42
 */

namespace App\Http\SingleActions\Frontend\User\Help;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUsersHelpCenter;
use Illuminate\Http\JsonResponse;

class UserHelpCenterAction
{
    protected $model;

    /**
     * @param  FrontendUsersHelpCenter  $frontendUsersHelpCenter
     */
    public function __construct(FrontendUsersHelpCenter $frontendUsersHelpCenter)
    {
        $this->model = $frontendUsersHelpCenter;
    }
    /**
     * 帮助中心菜单
     * @param  FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $data = $this->model::select('id','pid','menu')->get()->toArray();
        $menu = $this->model->toTree($data);
        return $contll->msgOut(true, $menu);
    }
}