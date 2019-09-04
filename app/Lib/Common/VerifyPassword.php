<?php
namespace App\Lib\Common;

class VerifyPassword
{
    /**
     * @param object $conll
     * @param string $newPassword
     * @return mixed
     */
    public static function verifyPassword($conll, $newPassword)
    {
        if (strlen($newPassword) > 16 || strlen($newPassword) < 6) {
            return $conll->msgOut(false, [], '100026');
        }
        if (preg_match('/^\d*$/', $newPassword)) {
            return $conll->msgOut(false, [], '100027');
        }
        if (preg_match('/^[a-z]*$/i',$newPassword)) {
            return $conll->msgOut(false, [], '100028');
        }
        if (!preg_match('/^[a-z\d]*$/i',$newPassword)) {
            return $conll->msgOut(false, [], '100029');
        }
    }
}
