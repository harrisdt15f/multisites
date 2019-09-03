<?php
namespace App\Lib\Common;

class VerifyPassword
{
    /**
     * @param object $conll
     * @param string $new_password
     * @return mixed
     */
    public static function verifyPassword($conll, $new_password)
    {
        if (strlen($new_password) > 16 || strlen($new_password) < 6) {
            return $conll->msgOut(false, [], '100026');
        }
        if (preg_match('/^\d*$/', $new_password)) {
            return $conll->msgOut(false, [], '100027');
        }
        if (preg_match('/^[a-z]*$/i',$new_password)) {
            return $conll->msgOut(false, [], '100028');
        }
        if (!preg_match('/^[a-z\d]*$/i',$new_password)) {
            return $conll->msgOut(false, [], '100029');
        }
    }
}
