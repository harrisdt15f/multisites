<?php

namespace App\Http\Controllers\BackendApi;

use App\Http\Requests\Backend\BackendAuthDeletePartnerAdminRequest;
use App\Http\Requests\Backend\BackendAuthRegisterRequest;
use App\Http\Requests\Backend\BackendAuthSelfResetPasswordRequest;
use App\Http\Requests\Backend\BackendAuthUpdatePAdmPasswordRequest;
use App\Http\Requests\Backend\BackendAuthUpdateUserGroupRequest;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class BackendAuthController extends BackEndApiMainController
{
    use AuthenticatesUsers;

    public $successStatus = 200;

    protected $eloqM = 'Admin\BackendAdminUser';

    /**
     * Login user and create token
     *
     * @param  Request  $request
     * @return JsonResponse [string] access_token
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['email', 'password']);
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
        $user->save();
        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expireAt,
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
     * @return JsonResponse
     */
    public function selfResetPassword(BackendAuthSelfResetPasswordRequest $request)
    {
        $inputDatas = $request->validated();
        if (!Hash::check($inputDatas['old_password'], $this->partnerAdmin->password)) {
            return $this->msgOut(false, [], '100003');
        } else {
            $token = $this->refresh();
            $this->partnerAdmin->password = Hash::make($inputDatas['password']);
            $this->partnerAdmin->remember_token = $token;
            try {
                $this->partnerAdmin->save();
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
     * @return JsonResponse
     */
    public function register(BackendAuthRegisterRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $group = BackendAdminAccessGroup::find($inputDatas['group_id']);
        $role = $group->role == '*' ? Arr::wrap($group->role) : Arr::wrap(json_decode($group->role, true));
        $isManualRecharge = false;
        $fundOperation = BackendSystemMenu::select('id')->where('route', '/manage/recharge')->first()->toArray();
        $isManualRecharge = in_array($fundOperation['id'], $role, true);
        $input = $inputDatas;
        $input['password'] = bcrypt($input['password']);
        $input['platform_id'] = $this->currentPlatformEloq->platform_id;
        $user = BackendAdminUser::create($input);
        if ($isManualRecharge === true) {
            $insertData = ['admin_id' => $user->id];
            $fundOperationEloq = new BackendAdminRechargePocessAmount();
            $fundOperationEloq->fill($insertData);
            $fundOperationEloq->save();
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
     * @return JsonResponse|null
     */
    public function updateUserGroup(BackendAuthUpdateUserGroupRequest $request) :  ? JsonResponse
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
     * details api
     *
     * @return Response
     */
    public function details() : Response
    {
        $user = $this->partnerAdmin;
        return $this->msgOut(true, $user);
    }

    /**
     * Logout user (Revoke the token)
     * @param  Request  $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request): JsonResponse
    {
        $throtleKey = Str::lower($this->username() . '|' . $request->ip());
        $request->session()->invalidate();
        $this->limiter()->clear($throtleKey);
        $this->currentAuth->logout();
        $this->currentAuth->invalidate();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
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
     * 删除管理员
     * @param  BackendAuthDeletePartnerAdminRequest $request
     * @return JsonResponse
     */
    public function deletePartnerAdmin(BackendAuthDeletePartnerAdminRequest $request):  ? JsonResponse
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
     * @param  BackendAuthUpdatePAdmPasswordRequest $request
     * @return JsonResponse
     */
    public function updatePAdmPassword(BackendAuthUpdatePAdmPasswordRequest $request) :  ? JsonResponse
    {
        $inputDatas = $request->validated();
        $targetUserEloq = $this->eloqM::where([
            ['id', '=', $inputDatas['id']],
            ['name', '=', $inputDatas['name']],
        ])->first();
        if ($targetUserEloq !== null) {
            $targetUserEloq->password = Hash::make($inputDatas['password']);
            if ($targetUserEloq->save()) {
//用户更新密码
                return $this->msgOut(true);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }
}
