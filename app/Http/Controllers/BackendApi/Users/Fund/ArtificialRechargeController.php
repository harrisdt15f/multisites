<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\AccountChange;
use App\Lib\Common\FundOperationRecharge;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\Message\BackendSystemNoticeList;
use App\Models\BackendAdminAuditFlowList;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\AccountChangeReport;
use App\Models\User\Fund\AccountChangeType;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersRechargeHistorie;
use App\Models\User\UsersRechargeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArtificialRechargeController extends BackEndApiMainController
{
    protected $eloqM = 'User\FrontendUser';
    protected $message = '有新的人工充值需要审核';
    //人工充值 用户列表
    public function users(): JsonResponse
    {
        $fixedJoin = 1;
        $withTable = 'account';
        $searchAbleFields = ['username', 'type', 'vip_level', 'is_tester', 'frozen_type', 'prize_group', 'level_deep', 'register_ip'];
        $withSearchAbleFields = ['balance'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $this->msgOut(true, $data);
    }

    //给用户人工充值
    public function recharge(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_users,id',
            'amount' => 'required|numeric|gt:0',
            'apply_note' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        };
        $userEloq = FrontendUser::find($this->inputs['id']);
        $partnerAdmin = $this->partnerAdmin;
        DB::beginTransaction();
        try {
            //普通管理员人工充值需要审核的操作
            if ($this->currentPartnerAccessGroup->role !== '*') {
                //扣除管理员额度
                $adminFundData = BackendAdminRechargePocessAmount::where('admin_id', $partnerAdmin->id)->first();
                if (is_null($adminFundData)) {
                    return $this->msgOut(false, [], '101100');
                }
                $adminOperationFund = $adminFundData->fund;
                //可操作额度小于充值额度
                if ($adminOperationFund < $this->inputs['amount']) {
                    return $this->msgOut(false, [], '101101');
                }
                $newFund = $adminOperationFund - $this->inputs['amount'];
                $adminFundEdit = ['fund' => $newFund];
                $adminFundData->fill($adminFundEdit);
                $adminFundData->save();
                //插入审核表
                $auditFlowID = $this->insertAuditFlow($partnerAdmin->id, $partnerAdmin->name, $this->inputs['apply_note']);
                //发送站内消息 提醒有权限的管理员审核
                $this->sendMessage();
            } else {
                //超管操作不需审核 直接给用户充值
                //检查是否存在 人工充值 的帐变类型表
                $accountChangeTypeEloq = AccountChangeType::where('sign', 'artificial_recharge')->first();
                if (is_null($accountChangeTypeEloq)) {
                    DB::rollBack();
                    return $this->msgOut(false, [], '100901');
                }
                //修改用户金额
                $UserAccounts = FrontendUsersAccount::where('user_id', $this->inputs['id'])->first();
                $balance = $UserAccounts->balance + $this->inputs['amount'];
                $UserAccountsEdit = ['balance' => $balance];
                $editStatus = FrontendUsersAccount::where(function ($query) use ($UserAccounts) {
                    $query->where('user_id', $UserAccounts->user_id)
                        ->where('updated_at', $UserAccounts->updated_at);
                })->update($UserAccountsEdit);
                //充值失败回滚
                if ($editStatus === 0) {
                    DB::rollBack();
                    return $this->msgOut(false, [], '101102');
                }
                //用户帐变表
                $accountChangeReportEloq = new AccountChangeReport();
                $accountChangeClass = new AccountChange();
                $accountChangeClass->addData($accountChangeReportEloq, $userEloq->toArray(), $this->inputs['amount'], $UserAccounts->balance, $balance, $accountChangeTypeEloq);
            }
            //添加人工充值明细表
            $auditFlowID = isset($auditFlowID) ? $auditFlowID : null;
            $newFund = isset($newFund) ? $newFund : null;
            $this->insertFundLog($partnerAdmin, $userEloq, $auditFlowID, $newFund);
            //用户 users_recharge_histories 表
            $deposit_mode = UsersRechargeHistorie::ARTIFICIAL;
            $companyOrderNum = $this->insertRechargeHistory($userEloq, $auditFlowID, $deposit_mode);
            // 用户 users_recharge_logs 表
            $this->insertRechargeLog($companyOrderNum, $deposit_mode);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //插入审核表
    public function insertAuditFlow($admin_id, $admin_name, $apply_note)
    {
        $insertData = [
            'admin_id' => $admin_id,
            'admin_name' => $admin_name,
            'apply_note' => $apply_note,
        ];
        $auditFlow = new BackendAdminAuditFlowList();
        $auditFlow->fill($insertData);
        $auditFlow->save();
        return $auditFlow->id;
    }

    /**
     * 生成充值订单号
     */
    public function createOrder()
    {
        return date('Ymd') . substr(time(), -4) . mt_rand(100000, 999999) . substr(uniqid(), -7);
    }

    /**
     * 插入users_recharge_histories
     * 人工充值 $deposit_mode=1 后面不需要在传参
     * @param $user_id
     * @param $user_name
     * @param $is_tester
     * @param $top_agent
     * @param $deposit_mode
     * @param $amount
     * @param $audit_flow_id
     */
    public function insertRechargeHistoryArr($user_id, $user_name, $is_tester, $top_agent, $amount, $audit_flow_id, $status, $deposit_mode, $channel = null, $payment_id = null, $real_amount = null, $fee = null)
    {
        $insertSqlArr = [
            'user_id' => $user_id,
            'user_name' => $user_name,
            'is_tester' => $is_tester,
            'top_agent' => $top_agent,
            'deposit_mode' => $deposit_mode,
            'company_order_num' => $this->createOrder(),
            'amount' => $amount,
            'audit_flow_id' => $audit_flow_id,
            'status' => $status,
        ];
        if ($deposit_mode === 0) {
            $insertDataArr = [
                'channel' => $channel,
                'payment_id' => $payment_id,
                'real_amount' => $real_amount,
                'fee' => $fee,
                'payment_id' => $payment_id,
            ];
            $insertSqlArr = array_merge($insertSqlArr, $insertDataArr);
        }
        return $insertSqlArr;
    }

    /**
     * 插入users_recharge_logs
     * 人工充值 $deposit_mode=1 后面不需要在传参
     * @param $company_order_num
     * @param $log_num
     * @param $deposit_mode
     * @param $req_type
     * @param $real_amount
     * @param $req_type_1_params
     * @param $req_type_2_params
     * @param $req_type_4_params
     */
    public function insertRechargeLogArr($company_order_num, $log_num, $deposit_mode, $req_type_1_params = null, $req_type_2_params = null, $req_type_4_params = null, $req_type = null, $real_amount = null)
    {
        $insertSqlArr = [
            'company_order_num' => $company_order_num,
            'log_num' => $log_num,
            'deposit_mode' => $deposit_mode,
        ];
        if ($deposit_mode === 0) {
            $insertDataArr = [
                'req_type_1_params' => $req_type_1_params,
                'req_type_2_params' => $req_type_2_params,
                'req_type_4_params' => $req_type_4_params,
                'req_type' => $req_type,
                'real_amount' => $real_amount,
            ];
            $insertSqlArr = array_merge($insertSqlArr, $insertDataArr);
        }
        return $insertSqlArr;
    }

    /**
     * 发送站内消息 提醒有权限的管理员审核
     * @return void
     */
    public function sendMessage()
    {
        $messageClass = new InternalNoticeMessage();
        $type = BackendSystemNoticeList::AUDIT;
        $roleId = BackendSystemMenu::where('en_name', 'recharge.check')->value('id');
        $allGroup = BackendAdminAccessGroup::select('id', 'role')->get();
        $groupIds = [];
        //获取有人工充值权限的组
        foreach ($allGroup as $group) {
            if ($group->role === '*') {
                $groupIds[] = $group->id;
            } else {
                $roleArr = json_decode($group->role, true);
                if (array_key_exists($roleId, $roleArr)) {
                    $groupIds[] = $group->id;
                }
            }
        }
        //获取有人工充值权限的管理员
        $admins = BackendAdminUser::select('id', 'group_id')->whereIn('group_id', $groupIds)->get();
        if (!is_null($admins)) {
            $messageClass->insertMessage($type, $this->message, $admins->toArray());
        }
    }

    /**
     * 插入充值额度记录
     * @param  object $partnerAdmin 管理员eloq
     * @param  object $userEloq     用户eloq
     * @param  int $auditFlowID  backend_admin_audit_flow_lists审核表id
     * @param  int $newFund  变动后的额度
     * @return void
     */
    public function insertFundLog($partnerAdmin, $userEloq, $auditFlowID, $newFund)
    {
        $rechargeLog = new BackendAdminRechargehumanLog();
        $type = $this->currentPartnerAccessGroup->role !== '*' ? BackendAdminRechargehumanLog::ADMIN : 3;
        $in_out = BackendAdminRechargehumanLog::DECREMENT;
        $comment = '[给用户人工充值]==>-' . $this->inputs['amount'] . '|[目前额度]==>' . $newFund;
        $fundOperationClass = new FundOperationRecharge();
        $fundOperationClass->insertOperationDatas($rechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $userEloq->id, $userEloq->nickname, $this->inputs['amount'], $comment, $auditFlowID);
    }

    /**
     * 插入users_recharge_histories表
     * @param  objact $userEloq    用户eloq
     * @param  int $auditFlowID backend_admin_audit_flow_lists审核表id
     * @param  int $deposit_mode 充值模式 0自动 1手动
     * @return string
     */
    public function insertRechargeHistory($userEloq, $auditFlowID, $deposit_mode)
    {
        $userRechargeHistory = new UsersRechargeHistorie();
        $status = $this->currentPartnerAccessGroup->role !== '*' ? UsersRechargeHistorie::UNDERWAYAUDIT : UsersRechargeHistorie::AUDITSUCCESS;
        $rechargeHistoryArr = $this->insertRechargeHistoryArr($userEloq->id, $userEloq->nickname, $userEloq->is_tester, $userEloq->top_id, $this->inputs['amount'], $auditFlowID, $status, $deposit_mode);
        $userRechargeHistory->fill($rechargeHistoryArr);
        $userRechargeHistory->save();
        return $userRechargeHistory->company_order_num;
    }

    /**
     * 插入users_recharge_logs表
     * @param  string $companyOrderNum 充值订单号
     * @param  int $deposit_mode 充值模式 0自动 1手动
     * @return [type]                  [description]
     */
    public function insertRechargeLog($companyOrderNum, $deposit_mode)
    {
        $rchargeLogeEloq = new UsersRechargeLog();
        $log_num = $this->log_uuid;
        $rechargeLogArr = $this->insertRechargeLogArr($companyOrderNum, $log_num, $deposit_mode);
        $rchargeLogeEloq->fill($rechargeLogArr);
        $rchargeLogeEloq->save();
    }
}
