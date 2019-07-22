<?php

namespace App\Models\User\Logics;

trait FrontendUserTraits
{
    /**
     * 获取所有用户id
     */
    public static function getAllUserIds()
    {
        return self::select('id')->get();
    }
}
