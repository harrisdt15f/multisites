<?php
namespace App\Lib\Common;

use App\Models\User\Fund\BackendAdminRechargehumanLog;

class FundOperation
{
    /**
     *
     * @param $eloqM
     * @param $type  0系统对管理操作   1超管对管理操作  2管理对用户操作  3超管对用户操作
     * @param $in_out  0减少   1增加
     * @param $OperationAdminId     操作人ID
     * @param $OperationAdminName   操作人NAME
     * @param $OperationId          被操作人ID
     * @param $OperationName        被操作人NAME
     * @param $amount               操作金额
     * @param $content              具体描述
     * @param $AuditFlow0ID         审核表id
     */
    public function insertOperationDatas(
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
        } elseif ($type === 3) {
            $OperationDatas['super_admin_id'] = $OperationAdminId;
            $OperationDatas['super_admin_name'] = $OperationAdminName;
            $OperationDatas['user_id'] = $OperationId;
            $OperationDatas['user_name'] = $OperationName;
            $OperationDatas['status'] = 1;
        }
        $eloqM->fill($OperationDatas);
        $eloqM->save();
    }

    /**
     * 查看该管理员今日 手动添加的充值额度是否在限额内
     * @param  int     $adminId       [需要充值的管理员id]
     * @param  folat   $rechargeFund  [充值的额度]
     * @return array
     */
    public function checkRechargeToday($adminId, $rechargeFund)
    {
        $maxRechargeFund = 90000;
        $today = date('Y-m-d');
        $rechargeFundToday = BackendAdminRechargehumanLog::select('amount')->where('type', 1)->where('admin_id', $adminId)->whereDate('created_at', $today)->sum('amount');
        if (($rechargeFundToday + $rechargeFund) > $maxRechargeFund) {
            $restRechargeFund = $maxRechargeFund - $rechargeFundToday;
            return ['success' => false, 'msg' => '管理员每日手动添加的最大充值额度为' . $maxRechargeFund . ',目前剩余额度' . $restRechargeFund];
        }
        return ['success' => true];
    }
}
