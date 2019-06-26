<?php

namespace App\Http\Controllers\BackendApi;

use App\Http\Requests\Backend\BackendAuthDeletePartnerAdminRequest;
use App\Http\Requests\Backend\BackendAuthRegisterRequest;
use App\Http\Requests\Backend\BackendAuthSelfResetPasswordRequest;
use App\Http\Requests\Backend\BackendAuthUpdatePAdmPasswordRequest;
use App\Http\Requests\Backend\BackendAuthUpdateUserGroupRequest;
use App\Http\SingleActions\Backend\BackendAuthAllUserAction;
use App\Http\SingleActions\Backend\BackendAuthDeletePartnerAdminAction;
use App\Http\SingleActions\Backend\BackendAuthRegisterAction;
use App\Http\SingleActions\Backend\BackendAuthSelfResetPasswordAction;
use App\Http\SingleActions\Backend\BackendAuthUpdatePAdmPasswordAction;
use App\Http\SingleActions\Backend\BackendAuthUpdateUserGroupAction;
use App\Models\Admin\BackendAdminUser;
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

    public $successStatus = '200';

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
     * @param  BackendAuthSelfResetPasswordRequest $request
     * @param  BackendAuthSelfResetPasswordAction  $action
     * @return JsonResponse
     */
    public function selfResetPassword(BackendAuthSelfResetPasswordRequest $request, BackendAuthSelfResetPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * Register api
     * @param  BackendAuthRegisterRequest $request
     * @param  BackendAuthRegisterAction  $action
     * @return JsonResponse
     */
    public function register(BackendAuthRegisterRequest $request, BackendAuthRegisterAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取所有当前平台的商户管理员用户
     * @param  BackendAuthAllUserAction  $action
     * @return JsonResponse
     */
    public function allUser(BackendAuthAllUserAction $action):  ? JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 修改管理员的归属组
     * @param  BackendAuthUpdateUserGroupRequest $request
     * @param  BackendAuthUpdateUserGroupAction  $action
     * @return JsonResponse
     */
    public function updateUserGroup(BackendAuthUpdateUserGroupRequest $request, BackendAuthUpdateUserGroupAction $action) : JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * details api
     *
     * @return Response
     */
    public function details(): Response
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
     * @param  BackendAuthDeletePartnerAdminAction  $action
     * @return JsonResponse
     */
    public function deletePartnerAdmin(BackendAuthDeletePartnerAdminRequest $request, BackendAuthDeletePartnerAdminAction $action):  ? JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param  BackendAuthUpdatePAdmPasswordRequest $request
     * @param  BackendAuthUpdatePAdmPasswordAction  $action
     * @return JsonResponse
     */
    public function updatePAdmPassword(BackendAuthUpdatePAdmPasswordRequest $request, BackendAuthUpdatePAdmPasswordAction $action) :  ? JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
