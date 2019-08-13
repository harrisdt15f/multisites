<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\Requests\Frontend\UserAgentCenter\UserBonusRequest;
use App\Http\Requests\Frontend\UserAgentCenter\UserDaysalaryRequest;
use App\Http\Requests\Frontend\UserAgentCenter\UserProfitsRequest;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserProfitsAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserDaysalaryAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserBonusAction;
use App\Http\Requests\Frontend\UserAgentCenter\UserAgentCenterRegisterLinkRequest;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterRegisterableLinkAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterRegisterLinkAction;
use Illuminate\Http\JsonResponse;

class UserAgentCenterController extends FrontendApiMainController
{

    /**
     * 用户团队盈亏
     * @param UserProfitsAction $action
     * @param UserProfitsRequest $request
     * @return JsonResponse
     */
    public function userProfits(UserProfitsAction $action, UserProfitsRequest $request): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * 用户日工资
     * @param UserDaysalaryAction $action
     * @param UserDaysalaryRequest $request
     * @return JsonResponse
     */

    public function userDaysalary(UserDaysalaryAction $action, UserDaysalaryRequest $request): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * 链接开户信息
     * @param UserAgentCenterRegisterableLinkAction $action
     * @return JsonResponse
     */
    public function registerableLink(UserAgentCenterRegisterableLinkAction $action):JsonResponse
    {
        return $action->execute($this);
    }


    /**
     * 生成开户链接
     * @param UserAgentCenterRegisterLinkRequest $request
     * @param UserAgentCenterRegisterLinkAction $action
     * @return JsonResponse
     */
    public function registerLink(
        UserAgentCenterRegisterLinkRequest $request,
        UserAgentCenterRegisterLinkAction $action
    ):JsonResponse {
        return $action->execute($this, $request->validated());
    }

    /**
     * 用户分红
     * @param UserBonusAction $action
     * @param UserBonusRequest $request
     * @return JsonResponse
     */
    public function userBonus(UserBonusAction $action, UserBonusRequest $request) : JsonResponse
    {
        return $action->execute($this, $request);
    }
}
