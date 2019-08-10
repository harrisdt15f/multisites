<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 9:31 PM
 */

namespace App\Models\Game\Lottery\Logics;
use App\Models\Game\Lottery\LotteryBasicWay;
use App\Models\Game\Lottery\LotterySeriesWay;
use Illuminate\Support\Str;

trait LotteryBasicMethodLogics
{

    protected $splitChar = '|';

    protected $splitCharInArea = ' ';

    /**
     * 按offset来截取中奖号码
     * @param  string  $sFullWinningNumber
     * @param  int  $iOffset
     * @return array|string
     */
    public function getWnNumber($sFullWinningNumber, $iOffset)
    {
        $sWnNumber = '';
        $sFunction = '';
        switch ($this->series_code) {
            case 'ssc':
            case 'k3':
            case 'sd':
                $sWnNumber = substr($sFullWinningNumber, (int)$iOffset, $this->digital_count);
                $sFunction = 'getWinningNumber'.ucfirst($this->series_code);
                break;
            case 'lotto':
                $aBalls = explode($this->splitCharInArea, $sFullWinningNumber);
                $aNeedBalls = [];
                $i = $iOffset;
                for ($j = 0; $j < $this->digital_count; $j++) {
                    $aNeedBalls[$j] = $aBalls[$i];
                    $i++;
                }
                $sWnNumber = implode($this->splitCharInArea, $aNeedBalls);
                $sFunction = 'getWinningNumber'.ucfirst($this->series_code);
                break;
        }
        return $sFunction === '' ? false : $this->$sFunction($sWnNumber);
    }

    //##################################################################
    /**
     * @param  LotterySeriesWay  $oSeriesWay
     * @param  LotteryBasicWay  $oBasicWay
     * @param $sWnNumber
     * @param $sBetNumber
     * @return float|int
     */
    public function getPrizeCount(LotterySeriesWay $oSeriesWay, LotteryBasicWay $oBasicWay, $sWnNumber, $sBetNumber)
    {
        $pFunction = 'getPrize'.ucfirst($this->series_code);
        $sFunction = 'prize'.$oBasicWay->function.ucfirst(Str::camel($this->wn_function));
        return $this->$pFunction($sFunction, $sBetNumber, $sWnNumber, $oSeriesWay);
    }

}