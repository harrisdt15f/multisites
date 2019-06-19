<?php

namespace App\Http\Controllers\BackendApi\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\UserHandleApplyResetUserFundPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleApplyResetUserPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleCommonAuditPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleCreateUserRequest;
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
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserHandleController extends BackEndApiMainController
{
    protected $eloqM = 'User\FrontendUser';
    protected $withNameSpace = 'Admin\BackendAdminAuditPasswordsList';

    /**
     * 创建总代时获取当前平台的奖金组
     * @return JsonResponse
     */
    public function getUserPrizeGroup(): JsonResponse
    {
        $data['min'] = $this->currentPlatformEloq->prize_group_min;
        $data['max'] = $this->currentPlatformEloq->prize_group_max;
        return $this->msgOut(true, $data);
    }

    /**
     *创建总代与用户后台管理员操作创建
     */
    public function createUser(UserHandleCreateUserRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $inputDatas['nickname'] = $inputDatas['username'];
        $inputDatas['password'] = bcrypt($inputDatas['password']);
        $inputDatas['fund_password'] = bcrypt($inputDatas['fund_password']);
        $inputDatas['platform_id'] = $this->currentPlatformEloq->platform_id;
        $inputDatas['sign'] = $this->currentPlatformEloq->platform_sign;
        $inputDatas['vip_level'] = 0;
        $inputDatas['parent_id'] = 0;
        $inputDatas['register_ip'] = request()->ip();
        DB::beginTransaction();
        try {
            $user = $this->eloqM::create($inputDatas);
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
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
//        $success['token'] = $user->createToken('前端')->accessToken;

    }

    /**
     * 用户管理的所有用户信息表
     * @return JsonResponse
     */
    public function usersInfo(): JsonResponse
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
     * 申请用户密码功能
     * @param  UserHandleApplyResetUserPasswordRequest $request
     * @return JsonResponse
     */
    public function applyResetUserPassword(UserHandleApplyResetUserPasswordRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, 1);
    }

    /**
     * 申请资金密码
     * @param  UserHandleApplyResetUserFundPasswordRequest $request
     * @return JsonResponse
     */
    public function applyResetUserFundPassword(UserHandleApplyResetUserFundPasswordRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, 2);
    }

    /**
     * 申请资金密码跟密码共用功能
     * @param  $inputDatas
     * @param  $type todo if type new added then should notice on error message
     * @return JsonResponse
     */
    public function commonHandleUserPassword($inputDatas, $type): JsonResponse
    {
        $applyUserEloq = $this->eloqM::find($inputDatas['id']);
        if ($applyUserEloq !== null) {
            $auditFlowEloq = new BackendAdminAuditFlowList();
            $adminApplyEloq = new BackendAdminAuditPasswordsList();
            //###################
            $adminApplyCheck = $adminApplyEloq::where([
                ['user_id', '=', $applyUserEloq->id],
                ['status', '=', 0],
                ['type', '=', $type],
            ])->exists();
            if ($adminApplyCheckEloq === true) {
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
            } catch (Exception $e) {
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
     * @return JsonResponse
     */
    public function appliedResetUserPasswordLists(): JsonResponse
    {
        return $this->commonAppliedPasswordHandle();
    }

    /**
     * 用户资金密码已申请列表
     * @return JsonResponse
     */
    public function appliedResetUserFundPasswordLists(): JsonResponse
    {
        return $this->commonAppliedPasswordHandle();
    }

    /**
     * @return JsonResponse
     */
    private function commonAppliedPasswordHandle(): JsonResponse
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

    /**
     * @param  UserHandleCommonAuditPasswordRequest $request
     * @return JsonResponse
     */
    public function commonAuditPassword(UserHandleCommonAuditPasswordRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $eloqM = $this->modelWithNameSpace($this->withNameSpace);
        $applyUserEloq = $eloqM::where([
            ['id', '=', $inputDatas['id']],
            ['type', '=', $inputDatas['type']],
            ['status', '=', 0],
        ])->first();
        if ($applyUserEloq !== null) {
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
            } catch (Exception $e) {
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
     * @param  UserHandleDeactivateRequest $request
     * @return JsonResponse
     */
    public function deactivate(UserHandleDeactivateRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $userEloq = $this->eloqM::find($inputDatas['user_id']);
        if ($userEloq !== null) {
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
            } catch (Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        }
    }

    /**
     * @param  UserHandleDeactivateDetailRequest $request
     * @return JsonResponse
     */
    public function deactivateDetail(UserHandleDeactivateDetailRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $userEloq = $this->eloqM::find($inputDatas['user_id']);
        if ($userEloq !== null) {
            $data = FrontendUsersPrivacyFlow::where('user_id', $inputDatas['user_id'])->whereBetween('created_at', $inputDatas['start_time'], $inputDatas['end_time'])->orderBy('created_at', 'desc')->get()->toArray();
            return $this->msgOut(true, $data);
        }

    }

    /**
     * 用户帐变记录
     * @param  UserHandleUserAccountChangeRequest $request
     * @return JsonResponse
     */
    public function userAccountChange(UserHandleUserAccountChangeRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $datas = AccountChangeReport::select('username', 'type_name', 'type_sign', 'amount', 'before_balance', 'balance', 'created_at')->with('changeType')->where('user_id', $inputDatas['user_id'])->whereBetween('created_at', $inputDatas['start_time'], $inputDatas['end_time'])->get()->toArray();
        foreach ($datas as $key => $report) {
            $datas[$key]['in_out'] = $report['change_type']['in_out'];
            unset($datas[$key]['type_sign'], $datas[$key]['change_type']);
        }
        return $this->msgOut(true, $datas);
    }

    /**
     * 用户充值记录
     * @param  UserHandleUserRechargeHistoryRequest $request
     * @return JsonResponse
     */
    public function userRechargeHistory(UserHandleUserRechargeHistoryRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $datas = UsersRechargeHistorie::select('user_name', 'amount', 'deposit_mode', 'status', 'created_at')->where('user_id', $inputDatas['user_id'])->whereBetween('created_at', $inputDatas['start_time'], $inputDatas['end_time'])->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    /**
     * 人工扣除用户资金
     * @param  UserHandleDeductionBalanceRequest $request
     * @return JsonResponse
     */
    public function deductionBalance(UserHandleDeductionBalanceRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        //检查是否存在 人工充值 的帐变类型表
        $isExistType = AccountChangeType::select('name', 'sign')->where('sign', 'artificial_deduction')->exists();
        if ($isExistType === false) {
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
            $editStatus = FrontendUsersAccount::where('user_id', $inputDatas['user_id'])->where('updated_at', $userAccountsEloq->updated_at)->update($editArr);
            if ($editStatus === 0) {
                return $this->msgOut(false, [], '100105');
            }
            //添加帐变记录
            $userEloq = $this->eloqM::select('id', 'sign', 'top_id', 'parent_id', 'rid', 'username')->where('id', $inputDatas['user_id'])->first();
            $accountChangeReportEloq = new AccountChangeReport();
            $accountChangeObj = new AccountChange();
            $accountChangeObj->addData($accountChangeReportEloq, $userEloq, $inputDatas['amount'], $userAccountsEloq->balance, $newBalance, $accountChangeTypeEloq);
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
