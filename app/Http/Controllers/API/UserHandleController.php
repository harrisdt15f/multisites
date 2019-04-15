<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\AuditFlow;
use App\models\HandleUserAccounts;
use App\models\PassworAuditLists;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserHandleController extends ApiMainController
{
    protected $eloqM = 'UserHandleModel';

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
     *创建总代与用户后台管理员操作创建
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
        DB::beginTransaction();
        try {
            $user = $this->eloqM::create($this->inputs);
            $user->rid = $user->id;
            $userAccountEloq = new HandleUserAccounts();
            $userAccountData = [
                'user_id' => $user->id,
                'balance' => 0,
                'frozen' => 0,
                'status' => 1
            ];
            $userAccountEloq = $userAccountEloq->fill($userAccountData);
            $userAccountEloq->save();
            $user->account_id = $userAccountEloq->id;
            $user->save();
            DB::commit();
            $data['name'] = $user->username;
            return $this->msgout(true, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
//        $success['token'] = $user->createToken('前端')->accessToken;

    }

    //用户管理的所有用户信息表
    public function usersInfo()
    {
        //target model to join
        $fixedJoin = 1;//number of joining tables
        $witTableCriterias = 'account:id,balance,frozen';
        $searchAbleFields = ['username', 'type', 'vip_level', 'is_tester', 'frozen_type', 'prize_group', 'level_deep', 'register_ip'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $witTableCriterias);
        return $this->msgout(true, $data);
    }

    /**
     * 18.申请用户密码功能
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyResetUserPassword()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'password' => 'required',
            'apply_note' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 401);
        }
        $applyUserEloq = $this->eloqM::find($this->inputs['id']);
        if (!is_null($applyUserEloq)) {
            $auditFlowEloq = new AuditFlow();
            $adminApplyEloq = new PassworAuditLists();
            //###################
            $adminApplyCheckEloq = $adminApplyEloq::where([
                ['user_id', '=', $applyUserEloq->id],
                ['status', '=', 0],
            ])->first();
            if (!is_null($adminApplyCheckEloq)) {
                return $this->msgout(false, [], '更改密码已有申请', '0002');
            }
            //###################
            $flowData = [
                'admin_id' => $this->partnerAdmin->id,
                'admin_name' => $this->partnerAdmin->name,
                'username' =>$applyUserEloq->username,
                'apply_note' => $this->inputs['apply_note'] ?? '',
            ];
            DB::beginTransaction();
            try {
                $auditResult = $auditFlowEloq->fill($flowData);
                $auditData = [
                    'type' => 1,
                    'user_id' => $applyUserEloq->id,
                    'audit_data' => Hash::make($this->inputs['password']),
                    'audit_flow_id' => $auditResult->admin_id,
                    'status' => 0,
                ];
                $adminApplyResult = $adminApplyEloq->fill($auditData);
                $auditResult->save();
                $adminApplyResult->save();
                DB::commit();
                return $this->msgout(true, []);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '没有此用户', '0002');
        }
    }

    /**
     * 用户已申请的密码列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function appliedResetUserPasswordLists()
    {
        //main model
        $eloqM = $this->modelWithNameSpace('PassworAuditLists');
        //target model to join
        $fixedJoin = 1;//number of joining tables
        $withTable = 'auditFlow';
        $witTableCriterias = $withTable.':id,admin_id,auditor_id,apply_note,auditor_note,updated_at,admin_name,auditor_name,username';
//        $witTableCriterias = 'id,admin_id,auditor_id,apply_note,auditor_note,updated_at';
        $searchAbleFields = ['type', 'status', 'created_at', 'updated_at'];
        $withSearchAbleFields = ['username'];
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable,$withSearchAbleFields);
        return $this->msgout(true, $data);
    }
}
