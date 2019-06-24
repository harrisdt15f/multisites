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
use App\Http\SingleActions\Backend\Users\UserHandleCreateUserAction;
use App\Http\SingleActions\Backend\Users\UserHandleDeactivateAction;
use App\Http\SingleActions\Backend\Users\UserHandleDeactivateDetailAction;
use App\Http\SingleActions\Backend\Users\UserHandleDeductionBalanceAction;
use App\Http\SingleActions\Backend\Users\UserHandleUserAccountChangeAction;
use App\Http\SingleActions\Backend\Users\UserHandleUserRechargeHistoryAction;
use App\Http\SingleActions\Backend\Users\UserHandleUsersInfoAction;
use App\Models\Admin\BackendAdminAuditPasswordsList;
use App\Models\BackendAdminAuditFlowList;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
     * 创建总代与用户后台管理员操作创建
     * @param  UserHandleCreateUserRequest $request
     * @param  UserHandleCreateUserAction  $action
     * @return JsonResponse
     */
    public function createUser(UserHandleCreateUserRequest $request, UserHandleCreateUserAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户管理的所有用户信息表
     * @param  UserHandleUsersInfoAction  $action
     * @return JsonResponse
     */
    public function usersInfo(UserHandleUsersInfoAction $action): JsonResponse
    {
        return $action->execute($this);
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
     * @param  UserHandleDeactivateAction  $action
     * @return JsonResponse
     */
    public function deactivate(UserHandleDeactivateRequest $request, UserHandleDeactivateAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户冻结记录
     * @param  UserHandleDeactivateDetailRequest $request
     * @param  UserHandleDeactivateDetailAction  $action
     * @return JsonResponse
     */
    public function deactivateDetail(UserHandleDeactivateDetailRequest $request, UserHandleDeactivateDetailAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户帐变记录
     * @param  UserHandleUserAccountChangeRequest $request
     * @param  UserHandleUserAccountChangeAction  $action
     * @return JsonResponse
     */
    public function userAccountChange(UserHandleUserAccountChangeRequest $request, UserHandleUserAccountChangeAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户充值记录
     * @param  UserHandleUserRechargeHistoryRequest $request
     * @param  UserHandleUserRechargeHistoryAction  $action
     * @return JsonResponse
     */
    public function userRechargeHistory(UserHandleUserRechargeHistoryRequest $request, UserHandleUserRechargeHistoryAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 人工扣除用户资金
     * @param  UserHandleDeductionBalanceRequest $request
     * @param  UserHandleDeductionBalanceAction  $action
     * @return JsonResponse
     */
    public function deductionBalance(UserHandleDeductionBalanceRequest $request, UserHandleDeductionBalanceAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
