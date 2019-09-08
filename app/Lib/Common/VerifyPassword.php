<?php
namespace App\Lib\Common;

class VerifyPassword
{
    /**
     * 前后台共用 用户密码   1 后台  2 前台
     * @param object $conll
     * @param string $newPassword
     * @param int $platformType
     * @return mixed
     */
    public static function verifyPassword($conll, $newPassword, $platformType)
    {
        if ($platformType === 1) {
            if (strlen($newPassword) > 16 || strlen($newPassword) < 6) {
                return $conll->msgOut(false, [], '100114');
            }
            if (preg_match('/^\d*$/', $newPassword)) {
                return $conll->msgOut(false, [], '100115');
            }
            if (preg_match('/^[a-z]*$/i',$newPassword)) {
                return $conll->msgOut(false, [], '100116');
            }
            if (!preg_match('/^[a-z\d]*$/i',$newPassword)) {
                return $conll->msgOut(false, [], '100117');
            }
        }
        if ($platformType === 2) {
            if (strlen($newPassword) > 16 || strlen($newPassword) < 6) {
                return $conll->msgOut(false, [], '100026');
            }
            if (preg_match('/^\d*$/', $newPassword)) {
                return $conll->msgOut(false, [], '100027');
            }
            if (preg_match('/^[a-z]*$/i', $newPassword)) {
                return $conll->msgOut(false, [], '100028');
            }
            if (!preg_match('/^[a-z\d]*$/i', $newPassword)) {
                return $conll->msgOut(false, [], '100029');
            }
        }
    }
}
