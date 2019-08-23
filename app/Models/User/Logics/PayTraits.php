<?php

namespace App\Models\User\Logics;

use App\Models\User\UsersRechargeHistorie;
use App\Models\User\UsersWithdrawHistorie;
use Illuminate\Support\Facades\Log;
use App\Lib\Pay\BasePay;

trait PayTraits
{

    /**
     * 生成充值订单
     * @param $amount
     * @param $channel
     * @param $from
     * @return bool|string
     */
    public static function createRechargeOrder($user, $amount, $channel, $from = "web")
    {
        try {
            $data['user_id'] = $user->id;
            $data['user_name'] = $user->username;
            $data['is_tester'] = $user->is_tester;
            $data['top_agent'] = $user->top_agent;
            $data['channel'] = $channel;
            $data['amount'] = $amount;
            $data['company_order_num'] = BasePay::createRechargeOrderNum();
            $data['client_ip'] = real_ip();
            $data['deposit_mode'] = 1;
            $data['status'] = UsersRechargeHistorie::AUTOMATIC ;
            $data['source'] = $from ;
            $resule = UsersRechargeHistorie::create($data);
        } catch (\Exception $e) {
            Log::channel('pay-recharge')->error('error-'.$e->getMessage().'|'.$e->getLine().'|'.$e->getFile());
            return false;
        }
        return $resule;
    }


    /**
     * 创建提现订单
     * @param obj $user
     * @param array $datas
     * @return string
     */
    public static function createWithdrawOrder($user, array $datas)
    {
        try {
            $data['user_id'] = $user->id;
            $data['username'] = $user->username;
            $data['is_tester'] = $user->is_tester;
            $data['top_id'] = $user->top_id;
            $data['parent_id'] = $user->parent_id;
            $data['rid'] = $user->rid;
            $data['amount'] = $datas['amount'];
//            $data['card_id'] = $datas['card_id'];
            $data['card_number'] = $datas['card_number'];
            $data['card_username'] = $datas['card_username'];
            $data['bank_sign'] = $datas['bank_sign'];
            $data['request_time'] = time();
            $data['order_id'] = BasePay::createWithdrawOrderNum();
            $data['client_ip'] = real_ip();
            $data['status'] = UsersWithdrawHistorie::WAIT ;
            $data['source'] =  $datas['from'] ?? 'web';

            $resule = UsersWithdrawHistorie::create($data);
        } catch (\Exception $e) {
            Log::channel('pay-recharge')->error('error-'.$e->getMessage().'|'.$e->getLine().'|'.$e->getFile());
            return false;
        }
        return $resule;
    }

    /**
     * 设置提现单的状态等数据
     * @param array $datas
     * @return bool
     */
    public static function setWithdrawOrder(array $datas)
    {
        $withdrawOrder = UsersWithdrawHistorie::where('id', '=', $datas['id'])->first();
        if ($withdrawOrder && $withdrawOrder->status != $datas['status']) {
            return $withdrawOrder->update($datas);
        }
        return false ;
    }
}
