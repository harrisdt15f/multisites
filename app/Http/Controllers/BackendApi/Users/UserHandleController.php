<?php

namespace App\Http\Controllers\BackendApi\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
// use App\Http\Requests\Backend\Users\UserHandleCreateUserRequest;
use App\Http\Requests\Backend\Users\UserHandleApplyResetUserFundPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleApplyResetUserPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleCommonAuditPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleDeactivateDetailRequest;
use App\Http\Requests\Backend\Users\UserHandleDeactivateRequest;
use App\Http\Requests\Backend\Users\UserHandleDeductionBalanceRequest;
use App\Http\Requests\Backend\Users\UserHandleUserAccountChangeRequest;
use App\Http\Requests\Backend\Users\UserHandleUserRechargeHistoryRequest;
use App\Lib\Common\AccountChange;
use App\Models\Admin\BackendAdminAuditPasswordsList;
use App\Models\Admin\FrontendUsersPrivacyFlow;
use App\Models\BackendAdminAuditFlowList;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\AccountChangeReport;
use App\Models\User\Fund\AccountChangeType;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UserRechargeHistory;
use App\Models\User\UsersRechargeHistorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserHandleController extends BackEndApiMainController
{
    protected $eloqM = 'User\FrontendUser';
    protected $withNameSpace = 'Admin\BackendAdminAuditPasswordsList';
    /**
     * 创建总代时获取当前平台的奖金组
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPrizeGroup()
    {
        $data['min'] = $this->currentPlatformEloq->prize_group_min;
        $data['max'] = $this->currentPlatformEloq->prize_group_max;
        return $this->msgOut(true, $data);
    }

    /**
     *创建总代与用户后台管理员操作创建
     */
    public function createUser()
    {
        // ############################################
        // $inputDatas = $request->validated();
        // validated $min $max 私有属性无法访问   需要处理
        $min = $this->currentPlatformEloq->prize_group_min;
        $max = $this->currentPlatformEloq->prize_group_max;
        $validator = Validator::make($this->inputs, [
            'username' => 'required|unique:frontend_users',
            'password' => 'required',
            'fund_password' => 'required',
            'is_tester' => 'required|numeric',
            'prize_group' => 'required|numeric|between:' . $min . ',' . $max,
            'type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $this->inputs['nickname'] = $this->inputs['username'];
        $this->inputs['password'] = bcrypt($this->inputs['password']);
        $this->inputs['fund_password'] = bcrypt($this->inputs['fund_password']);
        $this->inputs['platform_id'] = $this->currentPlatformEloq->platform_id;
        $this->inputs['sign'] = $this->currentPlatformEloq->platform_sign;
        $this->inputs['vip_level'] = 0;
        $this->inputs['parent_id'] = 0;
        $this->inputs['register_ip'] = request()->ip();
        DB::beginTransaction();
        try {
            $user = $this->eloqM::create($this->inputs);
            $user->rid = $user->id;
            $userAccountEloq = new FrontendUsersAccount();
            $userAccountData = [
                'user_id' => $user->id,
                'balance' => 0,
                'frozen' => 0,
                'status' => 1,
            ];
            $userAccountEloq = $userAccountEloq->fill($userAccountData);
            $userAccountEloq->save();
            $user->account_id = $userAccountEloq->id;
            $user->save();
            DB::commit();
            $data['name'] = $user->username;
            return $this->msgOut(true, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
//        $success['token'] = $user->createToken('前端')->accessToken;

    }

    //用户管理的所有用户信息表
    public function usersInfo()
    {
        //target model to join
        $fixedJoin = 1; //number of joining tables
        $withTable = 'account';
        $searchAbleFields = [
            'username',
            'type',
            'vip_level',
            'is_tester',
            'frozen_type',
            'prize_group',
            'level_deep',
            'register_ip',
        ];
        $withSearchAbleFields = ['balance'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable,
            $withSearchAbleFields);
        return $this->msgOut(true, $data);
    }

    /**
     * 18.申请用户密码功能
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyResetUserPassword(UserHandleApplyResetUserPasswordRequest $request)
    {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, 1);
    }

    /**
     * 20.申请资金密码
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyResetUserFundPassword(UserHandleApplyResetUserFundPasswordRequest $request)
    {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, 2);
    }

    /**
     * 申请资金密码跟密码共用功能
     * @param $inputDatas
     * @param $type todo if type new added then should notice on error message
     * @return \Illuminate\Http\JsonResponse
     */
    public function commonHandleUserPassword($inputDatas, $type)
    {
        $applyUserEloq = $this->eloqM::find($inputDatas['id']);
        if (!is_null($applyUserEloq)) {
            $auditFlowEloq = new BackendAdminAuditFlowList();
            $adminApplyEloq = new BackendAdminAuditPasswordsList();
            //###################
            $adminApplyCheckEloq = $adminApplyEloq::where([
                ['user_id', '=', $applyUserEloq->id],
                ['status', '=', 0],
                ['type', '=', $type],
            ])->first();
            if (!is_null($adminApplyCheckEloq)) {
                if ($type === 1) {
                    $code = '100100';
                } else {
                    if ($type === 2) {
                        $code = '100101';
                    }
                }
                return $this->msgOut(false, [], $code);
            }
            //###################
            $flowData = [
                'admin_id' => $this->partnerAdmin->id,
                'admin_name' => $this->partnerAdmin->name,
                'username' => $applyUserEloq->username,
                'apply_note' => $inputDatas['apply_note'] ?? '',
            ];
            DB::beginTransaction();
            try {
                $auditResult = $auditFlowEloq->fill($flowData);
                $auditResult->save();
                $auditData = [
                    'type' => $type,
                    'user_id' => $applyUserEloq->id,
                    'audit_data' => Hash::make($inputDatas['password']),
                    'audit_flow_id' => $auditResult->id,
                    'status' => 0,
                ];
                $adminApplyResult = $adminApplyEloq->fill($auditData);
                $adminApplyResult->save();
                DB::commit();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }

    /**
     * 用户已申请的密码列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function appliedResetUserPasswordLists()
    {
        return $this->commonAppliedPasswordHandle();
    }

    /**
     * 用户资金密码已申请列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function appliedResetUserFundPasswordLists()
    {
        return $this->commonAppliedPasswordHandle();
    }

    private function commonAppliedPasswordHandle()
    {
        //main model
        $eloqM = $this->modelWithNameSpace($this->withNameSpace);
        //target model to join
        $fixedJoin = 1; //number of joining tables
        $withTable = 'auditFlow';
        $witTableCriterias = $withTable . ':id,admin_id,auditor_id,apply_note,auditor_note,updated_at,admin_name,auditor_name,username';
        $searchAbleFields = ['type', 'status', 'created_at', 'updated_at'];
        $withSearchAbleFields = ['username'];
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $this->msgOut(true, $data);
    }

    public function auditApplyUserPassword()
    {
        return $this->commonAuditPassword();
    }

    public function auditApplyUserFundPassword()
    {
        return $this->commonAuditPassword();
    }

    public function commonAuditPassword(UserHandleCommonAuditPasswordRequest $request)
    {
        $inputDatas = $request->validated();
        $eloqM = $this->modelWithNameSpace($this->withNameSpace);
        $applyUserEloq = $eloqM::where([
            ['id', '=', $inputDatas['id']],
            ['type', '=', $inputDatas['type']],
            ['status', '=', 0],
        ])->first();
        if (!is_null($applyUserEloq)) {
            $auditFlowEloq = $applyUserEloq->auditFlow;
            //handle User
            $user = FrontendUser::find($applyUserEloq->user_id);
            if ($applyUserEloq->type == 1) {
                $user->password = $applyUserEloq->audit_data;
            } else {
                $user->fund_password = $applyUserEloq->audit_data;
            }
            DB::beginTransaction();
            try {
                if ($inputDatas['status'] == 1) {
                    $user->save();
                }
                $auditFlowEloq->auditor_id = $this->partnerAdmin->id;
                $auditFlowEloq->auditor_note = $tinputDatas['auditor_note'];
                $auditFlowEloq->auditor_name = $this->partnerAdmin->name;
                $auditFlowEloq->save();
                $applyUserEloq->status = $inputDatas['status'];
                $applyUserEloq->save();
                DB::commit();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100102');
        }
    }

    /**
     * 用户冻结账号功能
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate(UserHandleDeactivateRequest $request)
    {
        $inputDatas = $request->validated();
        $userEloq = $this->eloqM::find($inputDatas['user_id']);
        if (!is_null($userEloq)) {
            DB::beginTransaction();
            try {
                $userEloq->frozen_type = $inputDatas['frozen_type'];
                $userEloq->save();
                $userAdmitFlowLog = new FrontendUsersPrivacyFlow();
                $data = [
                    'admin_id' => $this->partnerAdmin->id,
                    'admin_name' => $this->partnerAdmin->name,
                    'user_id' => $userEloq->id,
                    'username' => $userEloq->username,
                    'comment' => $inputDatas['comment'],
                ];
                $userAdmitFlowLog->fill($data);
                $userAdmitFlowLog->save();
                DB::commit();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        }
    }

    public function deactivateDetail(UserHandleDeactivateDetailRequest $request)
    {
        $inputDatas = $request->validated();
        $userEloq = $this->eloqM::find($inputDatas['user_id']);
        if (!is_null($userEloq)) {
            $data = FrontendUsersPrivacyFlow::where('user_id', $inputDatas['user_id'])->where('created_at', '>=', $inputDatas['start_time'])->where('created_at', '<', $inputDatas['end_time'])->orderBy('created_at', 'desc')->get()->toArray();
            return $this->msgOut(true, $data);
        }

    }

    //用户帐变记录
    public function userAccountChange(UserHandleUserAccountChangeRequest $request)
    {
        $inputDatas = $request->validated();
        $datas = AccountChangeReport::select('username', 'type_name', 'type_sign', 'amount', 'before_balance', 'balance', 'created_at')->with('changeType')->where('user_id', $inputDatas['user_id'])->where('created_at', '>=', $inputDatas['start_time'])->where('created_at', '<', $inputDatas['end_time'])->get()->toArray();
        foreach ($datas as $key => $report) {
            $datas[$key]['in_out'] = $report['change_type']['in_out'];
            unset($datas[$key]['type_sign']);
            unset($datas[$key]['change_type']);
        }
        return $this->msgOut(true, $datas);
    }

    //用户充值记录
    public function userRechargeHistory(UserHandleUserRechargeHistoryRequest $request)
    {
        $inputDatas = $request->validated();
        $datas = UsersRechargeHistorie::select('user_name', 'amount', 'deposit_mode', 'status', 'created_at')->where('user_id', $inputDatas['user_id'])->where('created_at', '>=', $inputDatas['start_time'])->where('created_at', '<', $inputDatas['end_time'])->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //人工扣除用户资金
    public function deductionBalance(UserHandleDeductionBalanceRequest $request)
    {
        $inputDatas = $request->validated();
        //检查是否存在 人工充值 的帐变类型表
        $accountChangeTypeEloq = AccountChangeType::select('name', 'sign')->where('sign', 'artificial_deduction')->first();
        if (is_null($accountChangeTypeEloq)) {
            return $this->msgOut(false, [], '100103');
        }
        $userAccountsEloq = FrontendUsersAccount::where('user_id', $inputDatas['user_id'])->first();
        if ($userAccountsEloq->balance < $inputDatas['amount']) {
            return $this->msgOut(false, [], '100104');
        }
        DB::beginTransaction();
        try {
            //扣除金额
            $newBalance = $userAccountsEloq->balance - $inputDatas['amount'];
            $editArr = ['balance' => $newBalance];
            $editStatus = FrontendUsersAccount::where(function ($query) use ($userAccountsEloq) {
                $query->where('user_id', $inputDatas['user_id'])
                    ->where('updated_at', $userAccountsEloq->updated_at);
            })->update($editArr);
            if ($editStatus === 0) {
                return $this->msgOut(false, [], '100105');
            }
            //添加帐变记录
            $userEloq = $this->eloqM::select('id', 'sign', 'top_id', 'parent_id', 'rid', 'username')->where('id', $inputDatas['user_id'])->first();
            $accountChangeReportEloq = new AccountChangeReport();
            $accountChangeClass = new AccountChange();
            $accountChangeClass->addData($accountChangeReportEloq, $userEloq, $inputDatas['amount'], $userAccountsEloq->balance, $newBalance, $accountChangeTypeEloq);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
