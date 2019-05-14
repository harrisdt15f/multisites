<?php

namespace App\Http\Controllers\BackendApi;

use App\models\FundOperation;
use App\models\OauthAccessTokens;
use App\models\PartnerAdminGroupAccess;
use App\models\PartnerAdminUsers;
use App\models\PartnerMenus;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BackendAuthController extends BackEndApiMainController
{
    public $successStatus = 200;

    protected $eloqM = 'PartnerAdminUsers';

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
//        $remember = $request->get('remember_me');
        $credentials = request(['email', 'password']);
        if (!$token = $this->currentAuth->attempt($credentials)) {
            return $this->msgOut(false, [], '100002');
        }
        $this->currentAuth->logout();
        $token = $this->currentAuth->attempt($credentials);
//        $user = $request->user($this->currentGuard);
//        $tokenResult = $this->refreshActivatePartnerToken($user);
        $expireInMinute = $this->currentAuth->factory()->getTTL();
        $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expireAt
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
    public function selfResetPassword()
    {
        $validator = Validator::make($this->inputs, [
            'old_password' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        if (!Hash::check($this->inputs['old_password'], $this->partnerAdmin->password)) {
            return $this->msgOut(false, [], '100003');
        } else {
            $this->partnerAdmin->password = Hash::make($this->inputs['password']);
            try {
                $this->partnerAdmin->save();
                $token = $this->refresh();
                $expireInMinute = $this->currentAuth->factory()->getTTL();
                $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
                $data = [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => $expireAt,
                ];
                return $this->msgOut(true, $data);
            } catch (\Exception $e) {
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
    public function register(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'name' => 'required|unique:partner_admin_users',
            'email' => 'required|email|unique:partner_admin_users',
            'password' => 'required',
            'is_test' => 'required|numeric',
            'group_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $group = PartnerAdminGroupAccess::find($this->inputs['group_id']);
        $role = $group->role == '*' ? Arr::wrap($group->role) : Arr::wrap(json_decode($group->role, true));
        $isManualRecharge = false;
        $FundOperation = PartnerMenus::select('id')->where('route', '/manage/recharge')->first()->toArray();
        $isManualRecharge = in_array($FundOperation['id'], $role, true);
        $input = $this->inputs;
        $input['password'] = bcrypt($input['password']);
        $input['platform_id'] = $this->currentPlatformEloq->platform_id;
        $user = PartnerAdminUsers::create($input);
        if ($isManualRecharge === true) {
            $insertData = ['admin_id' => $user->id];
            $FundOperationEloq = new FundOperation();
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
    public function allUser(): ?JsonResponse
    {
        try {
            $data = $this->currentPlatformEloq->partnerAdminUsers;
            if (is_null($data)) {
                $result = Arr::wrap($data);
            } else {
                $result = $data->toArray();
            }
            return $this->msgOut(true, $result);
        } catch (\Exception $e) {
            return $this->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }

    /**
     * @return JsonResponse|null
     */
    public function updateUserGroup(): ?JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'group_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $targetUserEloq = $this->eloqM::find($this->inputs['id']);
        if (!is_null($targetUserEloq)) {
            $targetUserEloq->group_id = $this->inputs['group_id'];
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
    public function details(): Response
    {
        $user = $this->partnerAdmin;
        return $this->msgOut(true, $user);
    }

    /**
     * Logout user (Revoke the token)
     * @return JsonResponse [string] message
     */
    public function logout(): JsonResponse
    {
        $this->currentAuth->logout();
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

    public function deletePartnerAdmin(): ?JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }

        $targetUserEloq = $this->eloqM::where([
            ['id', '=', $this->inputs['id']],
            ['name', '=', $this->inputs['name']],
        ])->first();
        if (!is_null($targetUserEloq)) {
            $token = $targetUserEloq->token();
            if ($token) {
                $token->revoke(); //取消目前登录中的状态
            }
            OauthAccessTokens::clearOldToken($targetUserEloq->id); //删除相关登录的token
            if ($targetUserEloq->delete()) {
//删除用户
                return $this->msgOut(true);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }

    public function updatePAdmPassword(): ?JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $targetUserEloq = $this->eloqM::where([
            ['id', '=', $this->inputs['id']],
            ['name', '=', $this->inputs['name']],
        ])->first();
        if (!is_null($targetUserEloq)) {
            $targetUserEloq->password = Hash::make($this->inputs['password']);
            if ($targetUserEloq->save()) {
//用户更新密码
                return $this->msgOut(true);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }
}
