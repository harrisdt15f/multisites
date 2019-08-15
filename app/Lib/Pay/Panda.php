<?php

namespace App\Lib\Pay;

use App\Lib\Clog;

class Panda extends BasePay
{

    public $sign                    = "panda";


    public $rechargeCallbackNeedParams          = [
        'game_order_id'     => "required|min:4,max:32",
        'money'             => "required|numeric|regex:/^[0-9]+(.[0-9]{1,2})?$/",
        'sign'              => "required|min:6,max:64",
    ];

    public $constant        = [];


    public function __construct()
    {

        $merchantId     = configure("finance_merchant_id");//商户号
        $key            = configure("finance_key");//商户密匙
        $gateway        = configure("finance_gateway");//接口地址

        $this->constant = [
            'callback'      => Pay::getNotifyUrl($this->sign),
            'url'           => $gateway,
            'key'           => $key,
            'merchantId'    => $merchantId
        ];
        $this->constant['recharge_channel_url']     = $this->constant['url'] . 'recharge_channel';
        $this->constant['recharge_url']             = $this->constant['url'] . 'recharge';
        $this->constant['withdrawal_url']           = $this->constant['url'] . 'payment';
        $this->constant['withdrawal_query_url']     = $this->constant['url'] . 'payment_query';
    }

    public function renderSuccess()
    {
        echo "success";
        die;
    }

    public function renderFail()
    {
        echo "fail";
        die;
    }

    /**
     * 获取支付渠道
     * @param string $source
     * @param $user
     * @return array
     */
    public function getRechargeChannel($source = "phone", $user = [obj])
    {

        $params = [
            'merchant_id'   => $this->constant["merchantId"],
            'client_ip'     => real_ip(),
            'source'        => $source,
            'username'      => $user->username,
            'user_level'    => $user->level
        ];
        Clog::rechargeLog("recharge-channel:【充值通道，参数传递】".json_encode($params, true));
        $params['sign'] = $this->encrypt($params, $this->constant["key"]);
        $result         = curl_post($this->constant["recharge_channel_url"], $params);

        Clog::rechargeLog("recharge-channel:", $result);
        if ($result['status']) {
            $data = $result["data"];
            if ($data['status'] === "success") {
                return $data['data'];
            }
        }

        return [];
    }

    /**
     * 充值
     * @param $amount
     * @param $orderId
     * @param $channel
     * @param string $bankId
     * @param string $source
     * @return array|string
     */
    public function recharge($amount, $orderId, $channel, $bankId = "", $source = "phone")
    {
        $callbackUrl    = Pay::getCallbackUrl($this->sign);
        $url            = $this->constant['recharge_url'];
        $merchantId     = $this->constant['merchantId'];

        $param = [];
        $param['merchant_id']   = $merchantId;
        $param['amount']        = $amount;
        $param['order_id']      = $orderId;
        $param['source']        = "web";
        $param['channel']       = $channel;

        $param['callback_url']  = $callbackUrl;
        $param['client_ip']     = real_ip();
        $param['time']          = time();

        $key = $this->constant['key'];

        $param['sign'] = $this->encrypt($param, $key);

        $this->setRechargeParams($param);
        $this->initRechargeLog();

        $result = curl_post($url, $param, [
            'time_out' => 10
        ]);

        // 文件日志
        $logData = [
            'params'    => $param,
            'url'       => $url,
            'result'      => $result
        ];
        //{"status":"success","msg":"\u53d1\u8d77\u5145\u503c\u6210\u529f\uff01",
        //"data":{"error":0,"pay_order_id":"LOCCS1560704539","pay_url":"https:\/\/api.cqvip9.com\/v1_beta\/do\/1559704539"}}
        if ($result['status']) {
            $data = $result['data'];
            if ($data['status'] == "success") {
                $this->updateRechargeLog(['request_status' => 1,
                    'request_reason' => "发起充值成功",
                    "request_back" => json_encode($result['data'])]);
                return ['url'=> $data['data']['pay_url'], 'type'=> "url" ];
            } else {
                Clog::rechargeLog("Error-Panda-{$data['msg']}-", $logData);
                $this->updateRechargeLog(['request_status' => 2, 'request_reason' => $data['msg']]);
                return $data['msg'];
            }
        } else {
            $this->updateRechargeLog(['request_status' => 2, 'request_reason' => $result['msg']]);
            Clog::rechargeLog("Error-Panda-{$result['msg']}-", $logData);
        }

        $this->updateRechargeLog(['request_status' => 2, 'request_reason' => $result['msg']]);

        return $result['msg'];
    }

    /**
     * 由于每个第三方接受的值都不一样， 此方法只处理接收的字段名称，外层会有try catch 捕获异常
     * @return array 订单号, 三方订单号, 金额
     */
    public function receive()
    {
        $body = file_get_contents("php://input");
        $params = json_decode($body, true);

//        $params = Input::all();
        $orderId = $params['game_order_id'];
        $trxId = $params['game_order_id'];
        $amt = $params['money'];
        if (isset($params['status']) && $params['status'] === 1) {
            return [$orderId, $trxId, $amt];
        }
        echo 'invalid';
        exit;
    }

    /**
     * 检查回调的参数
     * @return bool
     */
    public function checkRechargeCallbackParams()
    {
        $params = $this->rechargeCallbackParams;
        Clog::rechargeCallback("panda", "接受的参数", $params);

        $needParams = $this->rechargeCallbackNeedParams;
        $validator  = \Validator::make($params, $needParams);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return true;
    }

    public function processOrder()
    {
        $data   = $this->rechargeCallbackParams;

        // 检查订单
        $order  = $this->rechargeOrder;

        if (!in_array($order->status, [0, 1], true)) {
            Clog::rechargeCallback("panda", "订单已经处理-" . $order->status);
            return "订单已经处理-" . $order->status;
        }

        // 检测金额
        $amount = intval($data['money'] * 10000);
        if ($order->amount != $amount) {
            Clog::rechargeCallback("panda", "订单金额不符合-" . $order->amount, $data);
            return "订单金额不符合-" . $order->amount;
        }

        // 处理订单
        $res = $order->process($amount, 0, "");
        if (true !== $res) {
            Clog::rechargeCallback("panda", "处理订单失败:$res");
            return $res;
        }

        return true;
    }

    protected $platform_sign = null;

    //渠道id
    public function setSign($sign)
    {
        $this->platform_sign = $sign;
        return $this;
    }

    public function withdrawal($iBankId, $sCompanyOrderNum, $fAmount, $sCardNum, $sCardName)
    {
        $key = $this->constant['key'];
        $url = $this->constant['withdrawal_url'];
        $merchant = $this->constant['merchantId'];

        $params = [
            'merchant_id'   => $merchant,
            'order_id'      => $sCompanyOrderNum,
            'source'        => 'web',
            'amount'        => $fAmount,
            'bank_sign'     => $iBankId,
            'card_number'   => $sCardNum,
            'card_username' => $sCardName,
            'client_ip' => real_ip(),
        ];

        if ($this->platform_sign) {
            $params['platform_sign'] = $this->platform_sign;
        }

        $params['sign'] = $this->encrypt($params, $key);

        $this->setWithdrawParams($params);
        $this->initWithdrawLog();

        // 文件日志
        $logData = [
            'url' => $url,
            'post' => $params,
        ];

        Clog::withdrawLog("Panda-准备发起请求-", $logData);

        $data = curl_post($url, $params, [
            'time_out' => 10,
        ]);

        // 文件日志
        $logData = [
            'response' => $data,
        ];

        Clog::withdrawLog("Panda-请求结果-", $logData);

        if ($data['status']) {
            if ($data['data']["status"] == 'success') {
                $this->updateWithdrawLog(['request_status' => 1, 'request_reason' => $data['msg']]);
                return ['status' => true, 'msg' => $data["data"]['msg'], 'data' => []];
            }
            $this->updateWithdrawLog(['request_status' => 2, 'request_reason' => $data["data"]['msg']]);
            return ['status' => false, 'msg' => $data["data"]['msg'], 'data' => []];
        }

        $this->updateWithdrawLog(['request_status' => 2, 'request_reason' => $data['msg']]);
        return ['status' => false, 'msg' => $data["msg"], 'data' => $data["msg"]];
    }


    /**
     * 体现单查询
     * @param $oWithdrawal
     * @param null $channel
     * @return array
     */
    public function queryWithdrawOrderStatus($oWithdrawal, $channel = null)
    {

        $key = $this->constant['key'];
        $url = $this->constant['withdrawal_query_url'];
        $merchant = $this->constant['merchantId'];

        $params = [
            'merchant_id'   => $merchant,
            'order_id'      => $oWithdrawal->order_id,
            'client_ip'     => real_ip(),
        ];

        $params['sign'] = $this->encrypt($params, $key);

        $this->setWithdrawQueryParams($params);

        $returnData = curl_post($url, $params, []);

        $this->updateWithdrawQueryLog(['content' => json_encode($returnData)]);

        Clog::withdrawQueryLog("请求结果", $returnData);

        if (isset($returnData["status"])) {
            $data = $returnData['data'];
            if ($data['status'] == "success") {
                $this->updateWithdrawQueryLog(['back_status' => 1, 'back_reason' => "成功"]);
                return ['amount' => $data['data']['amount'], 'status' => $data['data']['result_status']];
            } else {
                $this->updateWithdrawQueryLog(['back_status' => 2, 'back_reason' => $data["msg"]]);
                return ['status' => -1, 'msg' => $data["msg"]];
            }
        } else {
            $this->updateWithdrawQueryLog(['back_status' => 2, 'back_reason' => $returnData["msg"]]);
            return ['status' => -1, 'msg' => $returnData["msg"]];
        }
    }

    public function encrypt(Array $data, $signKey)
    {
        $str = "";
        ksort($data);
        foreach ($data as $key => $value) {
            if ('sign' == $key || self::isEmpty($value)) {
                continue;
            }
            $str .= $key . "=" . $value . "&";
        }
        $str .= "key={$signKey}";

        return md5($str);
    }

    // 异步验签方法
    public function checkRechargeCallbackSign($keySign = 'sign')
    {
        $data   = $this->rechargeCallbackParams;
        $key    = $this->constant['key'];
        $mySign = $this->encrypt($data, $key);
        if (isset($data[$keySign]) && !empty($data[$keySign]) && $data[$keySign] == $mySign) {
            return true;
        }
        return false;
    }

    public function checkCurl($result, $info = null)
    {
        if (!empty($info) && $info['http_code'] != 200) {
            return ['status' => -5, 'msg' => '线路异常，无法获取交易结果', 'data' => '000'];
        }
        if ($result === false) {
            return ['status' => -5, 'msg' => '线路异常，无法获取交易结果', 'data' => '000'];
        }
        return null;
    }

    public static function isEmpty($value)
    {
        return $value === null || $value === [] || $value === '';
    }
}
