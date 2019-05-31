<?php namespace App\Lib;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Clog  {


    /** ================== 游戏 =================== */

    /**
     * 游戏错误专用
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function gameError($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/game/error-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }


    static function issueGen($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/issue/gen-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /** ================== 用户日志 =================== */

    /**
     * 投注日志
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function userBet($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/user/bet-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }


    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function statistics($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/statistics/laravel-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function simulation($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/simulation/laravel-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function recharge($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/recharge/laravel-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function withdraw($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/withdraw/laravel-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function notify($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/notify/laravel-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function rechargeLog($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/finance/recharge-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function withdrawLog($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/finance/withdraw-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function withdrawQueryLog($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/finance/withdraw-query-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $sign
     * @param $msg
     * @param array $data
     * @throws \Exception
     */
    static function rechargeCallback($sign, $msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/finance/callback-{$sign}-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    static function account($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/account/{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
        return true;
    }

    /**
     * @param $msg
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    static function lockError($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/account/locker-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
        return true;
    }

    /**
     * @param $msg
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    static function userAddChild($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/user/add-child-{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
        return true;
    }

    /**
     * @param $msg
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    static function partnerLog($msg, $data = []) {
        $dateStr = date("Y-m-d");
        $logFile = "logs/partner/{$dateStr}.log";
        self::writeLog($logFile, $msg, $data);
        return true;
    }

    /**
     * 写入日志
     * @param $path
     * @param $msg
     * @param $context
     * @throws \Exception
     */
    static function writeLog($path, $msg, $context) {
        $logger = new Logger('custom_log');
        $logger->pushHandler(new StreamHandler(storage_path($path)), Logger::INFO);
        $logger->info($msg, $context);
    }
}
