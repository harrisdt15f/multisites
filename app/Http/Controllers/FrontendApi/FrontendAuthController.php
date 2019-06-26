<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\Requests\Frontend\FrontendAuthDeletePartnerAdminRequest;
use App\Http\Requests\Frontend\FrontendAuthRegisterRequest;
use App\Http\Requests\Frontend\FrontendAuthResetFundPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthResetSpecificInfosRequest;
use App\Http\Requests\Frontend\FrontendAuthResetUserPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthSelfResetPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthUpdatePAdmPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthUpdateUserGroupRequest;
use App\Http\SingleActions\Frontend\FrontendAuthResetSpecificInfosAction;
use App\Http\SingleActions\Frontend\FrontendAuthUserSpecificInfosAction;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\SystemConfiguration;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class FrontendAuthController extends FrontendApiMainController
{
    use AuthenticatesUsers;

    public $successStatus = 200;

    public $eloqM = 'User\FrontendUser';

    public function username()
    {
        return 'username';
    }

    /**
     * Login user and create token
     *
     * @param  Request  $request
     * @return JsonResponse [string] access_token
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string|alpha_dash',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['username', 'password']);
        $this->maxAttempts = 1; //1 times
        $this->decayMinutes = 1; //1 minutes
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            return $this->msgOut(false, [], '100005');
        }
        if (!$token = $this->currentAuth->attempt($credentials)) {
            return $this->msgOut(false, [], '100002');
        }
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        $expireInMinute = $this->currentAuth->factory()->getTTL();
        $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
        $user = $this->currentAuth->user();
        if ($user->remember_token !== null) {
            try {
                JWTAuth::setToken($user->remember_token);
                JWTAuth::invalidate();
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }
        }
        $user->remember_token = $token;
        $user->last_login_ip = request()->ip();
        $user->last_login_time = Carbon::now()->timestamp;
        $user->save();
        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expireAt,
        ];
        return $this->msgOut(true, $data);
    }

    public function userDetail()
    {
        $user = $this->currentAuth->user();
        $account = $user->account;
        $balance = $account->balance;
        $frozen = $account->frozen;
        $data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'prize_group' => $user->prize_group,
            'user_type' => $user->type,
            'is_tester' => $user->is_tester,
            'last_login_time' => $user->last_login_time->toDateTimeString(),
            'levels' => $user->levels,
            'can_withdraw' => $user->frozen_type <= 0, //$user->frozen_type > 0 ? false : true
            'today_withdraw' => 0, //
            'daysalary_percentage' => 0,
            'bonus_percentage' => 0,
            'allowed_transfer' => true,
            'balance' => sprintf('%1.4f', substr($balance, 0, strrpos($balance, '.', 0) + 1 + 4)),
            'frozen_balance' => sprintf('%1.4f', substr($frozen, 0, strrpos($frozen, '.', 0) + 1 + 4)),
            'has_funds_password' => $user->fund_password ? true : false,
            'download_url' => SystemConfiguration::getConfigValue('app_download_url') . '/' . $user->invite_code,
            'version' => SystemConfiguration::getConfigValue('app_version'),
        ];
        return $this->msgOut(true, $data);
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
     * Register api
     * @param  FrontendAuthRegisterRequest $request
     * @return JsonResponse
     */
    public function register(FrontendAuthRegisterRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $group = BackendAdminAccessGroup::find($inputDatas['group_id']);
        $role = $group->role == '*' ? Arr::wrap($group->role) : Arr::wrap(json_decode($group->role, true));
        $isManualRecharge = false;
        $FundOperation = PartnerMenus::select('id')->where('route', '/manage/recharge')->first()->toArray();
        $isManualRecharge = in_array($FundOperation['id'], $role, true);
        $input = $inputDatas;
        $input['password'] = bcrypt($input['password']);
        $input['platform_id'] = $this->currentPlatformEloq->platform_id;
        $user = BackendAdminUser::create($input);
        if ($isManualRecharge === true) {
            $insertData = ['admin_id' => $user->id];
            $FundOperationEloq = new BackendAdminRechargePocessAmount();
            $FundOperationEloq->fill($insertData);
            $FundOperationEloq->save();
        }
        $credentials = request(['email', 'password']);
        $token = $this->currentAuth->attempt($credentials);
        $success['token'] = $token;
        $success['name'] = $user->name;
        return $this->msgOut(true, $success);
    }

    /**
     * 获取所有当前平台的商户管理员用户
     * @return JsonResponse
     */
    public function allUser():  ? JsonResponse
    {
        try {
            $data = $this->currentPlatformEloq->partnerAdminUsers;
            if (is_null($data)) {
                $result = Arr::wrap($data);
            } else {
                $result = $data->toArray();
            }
            return $this->msgOut(true, $result);
        } catch (Exception $e) {
            return $this->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }

    /**
     * @param  FrontendAuthUpdateUserGroupRequest $request
     * @return JsonResponse|null
     */
    public function updateUserGroup(FrontendAuthUpdateUserGroupRequest $request) :  ? JsonResponse
    {
        $inputDatas = $request->validated();
        $targetUserEloq = $this->eloqM::find($inputDatas['id']);
        if ($targetUserEloq !== null) {
            $targetUserEloq->group_id = $inputDatas['group_id'];
            if ($targetUserEloq->save()) {
                $result = $targetUserEloq->toArray();
                return $this->msgOut(true, $result);
            }
        }
    }

    /**
     * Logout user (Revoke the token)
     * @param  Request  $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request) : JsonResponse
    {
        $throtleKey = Str::lower($this->username() . '|' . $request->ip());
        $request->session()->invalidate();
        $this->limiter()->clear($throtleKey);
        $this->currentAuth->logout();
        $this->currentAuth->invalidate();
        return $this->msgOut(true, []); //'Successfully logged out'
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

    public function deletePartnerAdmin(FrontendAuthDeletePartnerAdminRequest $request):  ? JsonResponse
    {
        $inputDatas = $request->validated();
        $targetUserEloq = $this->eloqM::where([
            ['id', '=', $inputDatas['id']],
            ['name', '=', $inputDatas['name']],
        ])->first();
        if ($targetUserEloq !== null) {
            $token = $targetUserEloq->token();
            if ($token) {
                $token->revoke(); //取消目前登录中的状态
            }
//            OauthAccessTokens::clearOldToken($targetUserEloq->id); //删除相关登录的token
            if ($targetUserEloq->delete()) {
//删除用户
                return $this->msgOut(true);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }

    /**
     * 用户修改登录密码
     * @param  FrontendAuthResetUserPasswordRequest $request
     * @return JsonResponse
     */
    public function resetUserPassword(FrontendAuthResetUserPasswordRequest $request) : JsonResponse
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
