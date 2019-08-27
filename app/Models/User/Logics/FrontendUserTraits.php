<?php

namespace App\Models\User\Logics;

use App\Lib\Pay\Pay;
use App\Models\Finance\UserRecharge;
use App\Models\Admin\Notice\FrontendMessageNotice;

trait FrontendUserTraits
{
    /**
     * 获取所有用户id
     */
    public static function getAllUserIds()
    {
        return self::select('id')->get();
    }

    /**
     * 发起充值
     * @param $amount
     * @param $channel
     * @param $bankSign
     * @param $from
     * @return bool|string
     */
    public function recharge($amount, $channel, $bankSign = "", $from = "web")
    {
        $order = UserRecharge::request($this, $amount * 10000, $channel, $bankSign, $from);

        if (!$order) {
            return '对不起, 生成订单失败!';
        }

        // 发起充值
        $pay    = Pay::getHandle();
        $pay->setRechargeOrder($order);
        $pay->setRechargeUser($this);

        $data   = $pay->recharge($amount, $order->order_id, $channel, $bankSign);
        if (!is_array($data)) {
            $order->status      = -1;
            $order->fail_reason = $data??'';
            $order->save();
            return"对不起, 充值失败-{$data}!";
        }

        $order->request_time = time();
        $order->save();

        return $data;
    }

    //用户未读站内信数量
    public function unreadMessageNum()
    {
        $message = $this->message;
        return $message->where('status',FrontendMessageNotice::STATUS_UNREAD)->count();
    }
}
