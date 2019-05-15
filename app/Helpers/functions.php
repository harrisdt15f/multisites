<?php

/**
 * 生成充值订单号
 */
function createRechargeOrderNumber()
{
    return date('Ymd') . substr(time(), -4) . mt_rand(100000, 999999) . substr(uniqid(), -7);
}

/**
 * 插入user_recharge_history
 * 人工充值 $deposit_mode=0 后面不需要在传参
 * @param $user_id
 * @param $user_name
 * @param $is_tester
 * @param $top_agent
 * @param $deposit_mode
 * @param $amount
 * @param $audit_flow_id
 */
function insertRechargeHistoryArr($user_id, $user_name, $is_tester, $top_agent, $amount, $audit_flow_id, $status, $deposit_mode, $channel = null, $payment_id = null, $real_amount = null, $fee = null)
{
    $insertSqlArr = [
        'user_id' => $user_id,
        'user_name' => $user_name,
        'is_tester' => $is_tester,
        'top_agent' => $top_agent,
        'deposit_mode' => $deposit_mode,
        'company_order_num' => createRechargeOrderNumber(),
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
 * 人工充值 $deposit_mode=0 后面不需要在传参
 * @param $company_order_num
 * @param $log_num
 * @param $deposit_mode
 * @param $req_type
 * @param $real_amount
 * @param $req_type_1_params
 * @param $req_type_2_params
 * @param $req_type_4_params
 */
function insertRechargeLogArr($company_order_num, $log_num, $deposit_mode, $req_type_1_params = null, $req_type_2_params = null, $req_type_4_params = null, $req_type = null, $real_amount = null)
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
 *
 * @param $eloqM
 * @param $type  0系统对管理操作   1超管对管理操作  2管理对用户操作
 * @param $in_out  0减少   1增加
 * @param $OperationAdminId     操作人ID
 * @param $OperationAdminName   操作人NAME
 * @param $OperationId          被操作人ID
 * @param $OperationName        被操作人NAME
 * @param $amount               操作金额
 * @param $content              具体描述
 * @param $AuditFlow0ID         审核表id
 */
function insertOperationDatas(
    $eloqM,
    $type,
    $in_out,
    $OperationAdminId,
    $OperationAdminName,
    $OperationId,
    $OperationName,
    $amount,
    $comment,
    $AuditFlow0ID
) {

    $OperationDatas = [
        'type' => $type,
        'in_out' => $in_out,
        'amount' => $amount,
        'comment' => $comment,
        'audit_flow_id' => $AuditFlow0ID,
    ];
    if ($type === 0) {
        $OperationDatas['admin_id'] = $OperationId;
        $OperationDatas['admin_name'] = $OperationName;
    } elseif ($type === 1) {
        $OperationDatas['super_admin_id'] = $OperationAdminId;
        $OperationDatas['super_admin_name'] = $OperationAdminName;
        $OperationDatas['admin_id'] = $OperationId;
        $OperationDatas['admin_name'] = $OperationName;
    } elseif ($type === 2) {
        $OperationDatas['admin_id'] = $OperationAdminId;
        $OperationDatas['admin_name'] = $OperationAdminName;
        $OperationDatas['user_id'] = $OperationId;
        $OperationDatas['user_name'] = $OperationName;
        $OperationDatas['status'] = 0;

    }
    $eloqM->fill($OperationDatas);
    $eloqM->save();
}
