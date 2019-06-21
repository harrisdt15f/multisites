<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 9:27 PM
 */

namespace App\Models\Game\Lottery\Logics;


use App\Models\Game\Lottery\LotteryBasicMethod;

trait SeriesMethodsLogics
{

    public function getWinningNumber($sFullWinningNumber)
    {
        $oBasicMethod = LotteryBasicMethod::find($this->basic_method_id);
        $aWinningNumber = $oBasicMethod->getWnNumber($sFullWinningNumber, (int)$this->offset);
        return $aWinningNumber;
    }

}