<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\UserHandleModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserHandleController extends ApiMainController
{
    /**
     * 创建总代时获取当前平台的奖金组
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPrizeGroup()
    {
        $data['min'] = $this->currentPlatformEloq->prize_group_min;
        $data['max'] = $this->currentPlatformEloq->prize_group_max;
        return $this->msgout(true, $data);
    }

    /**
     * Register api
     *
     * @return Response
     */
    public function createUser()
    {
        $min = $this->currentPlatformEloq->prize_group_min;
        $max = $this->currentPlatformEloq->prize_group_max;
        $validator = Validator::make($this->inputs, [
            'username' => 'required|unique:users',
            'password' => 'required',
            'fund_password' => 'required',
            'is_tester' => 'required|numeric',
            'prize_group' => 'required|numeric|between:' . $min . ',' . $max,
            'type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
        $this->inputs['nickname'] = $this->inputs['username'];
        $this->inputs['password'] = bcrypt($this->inputs['password']);
        $this->inputs['fund_password'] = bcrypt($this->inputs['fund_password']);
        $this->inputs['platform_id'] = $this->currentPlatformEloq->platform_id;
        $this->inputs['sign'] = $this->currentPlatformEloq->platform_sign;
        $this->inputs['vip_level'] = 0;
        $this->inputs['register_ip'] = request()->ip();
        $user = UserHandleModel::create($this->inputs);
        $user->rid = $user->id;
        $user->save();
//        $success['token'] = $user->createToken('前端')->accessToken;
        $data['name'] = $user->username;
        return $this->msgout(true, $data);
    }

    public function usersInfo()
    {
        $pageSize = $this->inputs['page_size'] ?? 20;
        return UserHandleModel::select('id', 'username', 'nickname', 'top_id', 'parent_id', 'rid', 'sign', 'platform_id', 'type', 'vip_level', 'is_tester', 'frozen_type', 'prize_group', 'level_deep', 'register_ip', 'last_login_ip', 'register_time', 'last_login_time', 'status', 'created_at')->paginate($pageSize);
    }


}
