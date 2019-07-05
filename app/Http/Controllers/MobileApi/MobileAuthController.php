<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\FrontendAuthResetFundPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthResetSpecificInfosRequest;
use App\Http\Requests\Frontend\FrontendAuthResetUserPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthSelfResetPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthSetFundPasswordRequest;
use App\Http\SingleActions\Frontend\FrontendAuthLoginAction;
use App\Http\SingleActions\Frontend\FrontendAuthLogoutAction;
use App\Http\SingleActions\Frontend\FrontendAuthResetSpecificInfosAction;
use App\Http\SingleActions\Frontend\FrontendAuthSetFundPasswordAction;
use App\Http\SingleActions\Frontend\FrontendAuthUserDetailAction;
use App\Http\SingleActions\Frontend\FrontendAuthUserSpecificInfosAction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class MobileAuthController extends FrontendApiMainController
{
    public $eloqM = 'User\FrontendUser';

    public function username()
    {
        return 'username';
    }

    /**
     * Login user and create token
     *
     * @param  Request                  $request
     * @param  FrontendAuthLoginAction  $action
     * @return JsonResponse [string]    access_token
     */
    public function login(Request $request, FrontendAuthLoginAction $action): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * 用户信息
     * @param  FrontendAuthUserDetailAction $action
     * @return JsonResponse
     */
    public function userDetail(FrontendAuthUserDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        $token = $this->currentAuth->refresh();
        return $token;
    }

    /**
     * change partner user Password
     * @param  FrontendAuthSelfResetPasswordRequest $request
     * @return JsonResponse
     */
    public function selfResetPassword(FrontendAuthSelfResetPasswordRequest $request)
    {
        $inputDatas = $request->validated();
        if (!Hash::check($inputDatas['old_password'], $this->partnerUser->password)) {
            return $this->msgOut(false, [], '100003');
        } else {
            $token = $this->refresh();
            $this->partnerUser->password = Hash::make($inputDatas['password']);
            $this->partnerUser->remember_token = $token;
            try {
                $this->partnerUser->save();
                $expireInMinute = $this->currentAuth->factory()->getTTL();
                $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
                $data = [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => $expireAt,
                ];
                return $this->msgOut(true, $data);
            } catch (Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        }
    }

    /**
     * Logout user (Revoke the token)
     * @param  Request  $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request, FrontendAuthLogoutAction $action): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * Get the authenticated User
     *
     * @param  Request  $request
     * @return JsonResponse [json] user object
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * 用户修改登录密码
     * @param  FrontendAuthResetUserPasswordRequest $request
     * @return JsonResponse
     */
    public function resetUserPassword(FrontendAuthResetUserPasswordRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, 1);
    }

    /**
     * 用户修改资金密码
     * @param  FrontendAuthResetFundPasswordRequest $request
     * @return JsonResponse
     */
    public function resetFundPassword(FrontendAuthResetFundPasswordRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, 2);
    }

    /**
     * 修改 用户密码1 资金密码2 共用处理
     * @param  array  $inputDatas
     * @param  int    $type
     * @return JsonResponse
     */
    public function commonHandleUserPassword($inputDatas, $type): JsonResponse
    {
        $targetUserEloq = $this->eloqM::find($this->partnerUser->id);
        if ($inputDatas['old_password'] === $inputDatas['new_password']) {
            return $this->msgOut(false, [], '100007');
        }
        if ($inputDatas['new_password'] !== $inputDatas['confirm_password']) {
            return $this->msgOut(false, [], '100008');
        }
        if ($type === 1) {
            $field = 'password';
            $oldPassword = $targetUserEloq->password;
            $token = $this->refresh();
            $targetUserEloq->remember_token = $token;
        } elseif ($type === 2) {
            $field = 'fund_password';
            $oldPassword = $targetUserEloq->fund_password;
        } else {
            return $this->msgOut(false, [], '100010');
        }
        //校验密码
        if (!Hash::check($inputDatas['old_password'], $oldPassword)) {
            return $this->msgOut(false, [], '100009');
        }
        //修改密码
        $targetUserEloq->$field = Hash::make($inputDatas['new_password']);
        if ($targetUserEloq->save()) {
            return $this->msgOut(true);
        } else {
            return $this->msgOut(false, [], '100011');
        }
    }

    /**
     * 用户是否设置了资金密码
     * @return JsonResponse
     */
    public function isExistFundPassword(): JsonResponse
    {
        if ($this->partnerUser->fund_password !== null) {
            $status = true;
        } else {
            $status = false;
        }
        return $this->msgOut(true, $status);
    }

    //用户设置资金密码
    public function setFundPassword(FrontendAuthSetFundPasswordRequest $request, FrontendAuthSetFundPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 用户个人信息
     * @param  FrontendAuthUserSpecificInfosAction $action
     * @return JsonResponse
     */
    public function userSpecificInfos(FrontendAuthUserSpecificInfosAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 用户设置个人信息
     * @param  FrontendAuthResetSpecificInfosRequest $request
     * @param  FrontendAuthResetSpecificInfosAction  $action
     * @return JsonResponse
     */
    public function resetSpecificInfos(FrontendAuthResetSpecificInfosRequest $request, FrontendAuthResetSpecificInfosAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
