<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 9:31 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryBasicWay;
use App\Models\Game\Lottery\LotteryPrizeLevel;
use App\Models\Game\Lottery\LotterySeriesWay;
use Illuminate\Support\Str;

trait LotteryBasicMethodLogics
{

    protected $splitChar = '|';
    protected $splitCharSumDigital = ',';
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
            case 'p3p5':
                $sWnNumber = substr(
                    $sFullWinningNumber,
                    $iOffset,
                    $this->digital_count
                );
                break;
            case 'pk10':
                $sWnNumber = explode($this->splitCharSumDigital, $sFullWinningNumber);
                if (is_array($sWnNumber)) {
                    $sWnNumber = array_slice($sWnNumber, $iOffset, $this->digital_count);
                }
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
                break;
        }
        $sFunction = 'getWinningNumber' . ucfirst($this->series_code);
        return $sFunction === '' ? false : $this->$sFunction($sWnNumber);
    }

    //##################################################################

    /**
     * @param  LotterySeriesWay  $oSeriesWay
     * @param  LotteryBasicWay  $oBasicWay
     * @param  string $sWnNumber
     * @param  LotterySeriesWay $sBetNumber
     * @return float|int
     */
    public function getPrizeCount(LotterySeriesWay $oSeriesWay, LotteryBasicWay $oBasicWay, $sWnNumber, $sBetNumber)
    {
        $pFunction = 'getPrize' . ucfirst($this->series_code);
        $sFunction = 'prize' . $oBasicWay->function . ucfirst(Str::camel($this->wn_function));
        return $this->$pFunction($sFunction, $sBetNumber, $sWnNumber, $oSeriesWay);
    }

    /**
     * 获取奖级列表,键为规则,值为奖级
     * @return array
     */
    public function getPrizeLevels(): array
    {
        $oLevels = LotteryPrizeLevel::where('basic_method_id', $this->id)->orderBy('level', 'asc')->get([
            'id',
            'level',
            'rule',
        ]);
        $aLevels = [];
        foreach ($oLevels as $oLevel) {
            $arrRule = explode(',', $oLevel->rule);
            foreach ($arrRule as $sRule) {
                $aLevels[$sRule] = $oLevel->level;
            }
        }
        return $aLevels;
    }
}
