<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\Requests\Frontend\UserAgentCenter\UserDaysalaryRequest;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserProfitsAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserDaysalaryAction;
use Illuminate\Http\{JsonResponse};
use App\Http\Requests\Frontend\UserAgentCenter\UserProfitsRequest;

class UserAgentCenterController extends FrontendApiMainController
{

    /**
     * 用户团队盈亏
     * @param UserProfitsAction $action
     * @param UserProfitsRequest $request
     * @return JsonResponse
     */
    public function UserProfits(UserProfitsAction $action , UserProfitsRequest $request): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * 用户日工资
     * @param UserDaysalaryAction $action
     * @param UserDaysalaryRequest $request
     * @return JsonResponse
     */

    public function UserDaysalary(UserDaysalaryAction $action , UserDaysalaryRequest $request): JsonResponse
    {
        return $action->execute($this, $request);
    }
}
