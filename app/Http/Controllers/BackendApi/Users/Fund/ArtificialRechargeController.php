<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\FundOperationRecharge;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\Fund\FundOperation;
use App\Models\Admin\Message\NoticeMessage;
use App\Models\Admin\PartnerAdminGroupAccess;
use App\Models\Admin\PartnerAdminUsers;
use App\Models\AuditFlow;
use App\Models\DeveloperUsage\Menu\PartnerMenus;
use App\Models\User\Fund\ArtificialRechargeLog;
use App\Models\User\UserHandleModel;
use App\Models\User\UserRechargeHistory;
use App\Models\User\UserRechargeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArtificialRechargeController extends BackEndApiMainController
{
    protected $eloqM = 'User\UserHandleModel';
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
            'id' => 'required|numeric',
            'amount' => 'required|numeric|gt:0',
            'apply_note' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $userData = UserHandleModel::find($this->inputs['id']);
        if (is_null($userData)) {
            return $this->msgOut(false, [], '101100');
        }
        $partnerAdmin = $this->partnerAdmin;
        $adminFundData = FundOperation::where('admin_id', $partnerAdmin->id)->first();
        if (is_null($adminFundData)) {
            return $this->msgOut(false, [], '101101');
        }
        $adminOperationFund = $adminFundData->fund;
        //可操作额度小于充值额度
        if ($adminOperationFund < $this->inputs['amount']) {
            return $this->msgOut(false, [], '101102');
        }
        DB::beginTransaction();
        try {
            //扣除管理员额度
            $newFund = $adminOperationFund - $this->inputs['amount'];
            $adminFundEdit = ['fund' => $newFund];
            $adminFundData->fill($adminFundEdit);
            $adminFundData->save();
            //插入审核表
            $auditFlowID = $this->insertAuditFlow($partnerAdmin->id, $partnerAdmin->name, $this->inputs['apply_note']);
            //添加管理员额度明细表
            $ArtificialRechargeLog = new ArtificialRechargeLog();
            $type = ArtificialRechargeLog::ADMIN;
            $in_out = ArtificialRechargeLog::DECREMENT;
            $comment = '[给用户人工充值]==>-' . $this->inputs['amount'] . '|[目前额度]==>' . $newFund;
            $fundOperationClass = new FundOperationRecharge();
            $fundOperationClass->insertOperationDatas($ArtificialRechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $userData->id, $userData->nickname, $this->inputs['amount'], $comment, $auditFlowID);
            //用户 user_recharge_history 表
            $userRechargeHistory = new UserRechargeHistory();
            $deposit_mode = UserRechargeHistory::ARTIFICIAL;
            $status = UserRechargeHistory::UNDERWAYAUDIT;
            $audit_flow_id = $auditFlowID;
            $rechargeHistoryArr = $this->insertRechargeHistoryArr($userData->id, $userData->nickname, $userData->is_tester, $userData->top_id, $this->inputs['amount'], $audit_flow_id, $status, $deposit_mode);
            $userRechargeHistory->fill($rechargeHistoryArr);
            $userRechargeHistory->save();
            // 用户 user_recharge_log 表
            $rchargeLogeEloq = new UserRechargeLog();
            $log_num = $this->log_uuid;
            $rechargeLogArr = $this->insertRechargeLogArr($userRechargeHistory->company_order_num, $log_num, $deposit_mode);
            $rchargeLogeEloq->fill($rechargeLogArr);
            $rchargeLogeEloq->save();
            //发送站内消息 提醒有权限的管理员审核
            $this->sendMessage();
            DB::commit();
            return $this->msgOut(true, [], '200', '操作成功，请等待管理员审核');
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
        $auditFlow = new AuditFlow();
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
     * 插入user_recharge_history
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
     * 插入user_recharge_log
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

    //发送站内消息 提醒有权限的管理员审核
    public function sendMessage()
    {
        $messageClass = new InternalNoticeMessage();
        $type = NoticeMessage::AUDIT;
        $roleId = PartnerMenus::where('en_name', 'recharge.check')->value('id');
        $allGroup = PartnerAdminGroupAccess::select('id', 'role')->get();
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
        $admins = PartnerAdminUsers::select('id', 'group_id')->whereIn('group_id', $groupIds)->get();
        if (!is_null($admins)) {
            $messageClass->insertMessage($type, $this->message, $admins->toArray());
        }
    }
}
