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
