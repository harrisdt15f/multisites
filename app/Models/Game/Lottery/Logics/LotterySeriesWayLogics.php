<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/20/2019
 * Time: 6:13 PM
 */

namespace App\Models\Game\Lottery\Logics;


trait LotterySeriesWayLogics
{

    /**
     * @var array|bool
     */
    public $WinningNumber;

    public function setWinningNumber($aWnNumberOfMethods): array
    {
        $aWnNumbers = [];
        foreach ($this->series_method_ids as $iSeriesMethodId){
            if ($aWnNumberOfMethods[ $iSeriesMethodId ] === false){
                continue;
            }
            $aWnNumbers[ $iSeriesMethodId ] = $aWnNumberOfMethods[ $iSeriesMethodId ];
        }
        $this->WinningNumber = count($aWnNumbers) > 0 ? $aWnNumbers : false;
        return $aWnNumbers;
    }

}