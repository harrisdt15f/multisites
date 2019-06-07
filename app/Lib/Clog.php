<?php namespace App\Lib;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\Log;

class Clog  {


    /** ================== 游戏 =================== */

    /**
     * 游戏错误专用
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function gameError($msg, $data = []) {
        Log::channel('game')->error($msg,$data);
    }

    /** ================== 用户日志 =================== */

    /**
     * 投注日志
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function userBet($msg, $data = []) {
        Log::channel('bet')->error($msg,$data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function recharge($msg, $data = []) {
        Log::channel('recharge')->error($msg,$data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function withdraw($msg, $data = []) {
        Log::channel('withdraw')->error($msg,$data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function rechargeLog($msg, $data = []) {
        Log::channel('recharge')->error($msg,$data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function withdrawLog($msg, $data = []) {
        Log::channel('withdraw')->error($msg,$data);
    }


    /**
     * @param $msg
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    static function account($msg, $data = []) {
        Log::channel('account')->error($msg,$data);
        return true;
    }

    /**
     * @param $msg
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    static function lockError($msg, $data = []) {
        Log::channel('log')->error($msg,$data);
        return true;
    }

    /**
     * @param $msg
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    static function userAddChild($msg, $data = []) {
        Log::channel('addchild')->error($msg,$data);
        return true;
    }
}
