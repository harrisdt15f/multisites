<?php
namespace App\Lib\Pay;

use App\Models\Card;
use App\Models\Finance\RechargeLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Pay
{
    public static function getHandle()
    {
        return new Panda();
    }

    /**
     * 获取充值渠道
     * @param $user;
     * @return array
     */
    public static function getRechargeChannel($user)
    {
        $handle = new Panda();
        return $handle->getRechargeChannel($user, "phone");
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public static function getNotifyUrl($sign)
    {
        return configure("finance_notify_url") . "/" . $sign;
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public static function getCallbackUrl($sign)
    {
        return configure("finance_callback_url") . "/" . $sign;
    }

    /**
     * 是否充值平台
     * @param $channelSign
     * @return bool
     */
    public static function isRechargeChannel($channelSign)
    {
        $allChannel = self::getRechargeChannel();
        if ($channelSign && array_key_exists($channelSign, $allChannel)) {
            return true;
        }
        return false;
    }

    public static function getAllPlatform()
    {
        $payConfig = config('web.pay');
        return array_keys($payConfig);
    }

    /** ============= Curl 相关请求 ============ */

    /**
     * Curl
     * @param $url
     * @param bool $params
     * @return array|mixed
     */
    public static function curl($url, $params = false)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $response = curl_exec($curl); // 执行操作

        if ($response === false) {
            $error = curl_error($curl);
            return [
                'status' => "fail",
                'msg' => $error,
            ];
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode == 200) {
            return json_decode($response, true);
        }

        return [
            'status' => "fail",
            'msg' => "错误码:" . $httpCode,
        ];
    }
}
