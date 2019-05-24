<?php

namespace App\Models\Game\lottery\Traits;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/23/2019
 * Time: 10:18 PM
 */
trait LotteryLogics
{
    public function getFormatMode()
    {
        $modeConfig = config('game.main.modes');
        $currentModes = explode(',', $this->valid_modes);

        $data = [];
        foreach ($currentModes as $index) {
            $_mode = $modeConfig[$index];
            $data[$index] = $_mode;
        }

        return $data;
    }

    // 标识获取彩种
    public static function findBySign($sign)
    {
        return self::where('en_name', $sign)->first();
    }

}