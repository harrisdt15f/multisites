<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterAction;

use Illuminate\Http\{JsonResponse,Request};

class UserAgentCenterController extends FrontendApiMainController
{

    /**
     * 用户团队盈亏
     * @param UserAgentCenterAction $action
     * @param Request $request
     * @return JsonResponse
     */
    public function UserProfits(UserAgentCenterAction $action , Request $request): JsonResponse
    {
        return $action->execute($this, $request);
    }

}
