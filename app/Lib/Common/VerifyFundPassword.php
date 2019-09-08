<?php
namespace App\Lib\Common;

class VerifyFundPassword
{
    /**
     * 前后台共用 资金密码验证  1 后台  2 前台
     * @param object $conll
     * @param string $newPassword
     * @param int $platformType
     * @return mixed
     */
    public static function verifyFundPassword($conll, $newPassword, $platformType)
    {
        if ($platformType === 1) {
            if (strlen($newPassword) > 18 || strlen($newPassword) < 6) {
                return $conll->msgOut(false, [], '100118');
            }
            if (preg_match('/^\d*$/', $newPassword)) {
                return $conll->msgOut(false, [], '100119');
            }
            if (preg_match('/^[a-z]*$/i',$newPassword)) {
                return $conll->msgOut(false, [], '100120');
            }
            if (!preg_match('/^[a-z\d]*$/i',$newPassword)) {
                return $conll->msgOut(false, [], '100121');
            }
        }
        if ($platformType === 2) {
            if (strlen($newPassword) > 18 || strlen($newPassword) < 6) {
                return $conll->msgOut(false, [], '100030');
            }
            if (preg_match('/^\d*$/', $newPassword)) {
                return $conll->msgOut(false, [], '100031');
            }
            if (preg_match('/^[a-z]*$/i',$newPassword)) {
                return $conll->msgOut(false, [], '100032');
            }
            if (!preg_match('/^[a-z\d]*$/i',$newPassword)) {
                return $conll->msgOut(false, [], '100033');
            }
        }
    }
}
