<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/15/2019
 * Time: 2:41 AM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryPrizeDetail;
use App\Models\Game\Lottery\LotterySeriesWay;

trait LotteryPrizeGroupLogic
{
    /**
     * @param string $iClassicPrize
     * @param string $series_id
     * @return bool
     */
    public static function getPrizeGroupByClassicPrizeAndSeries($iClassicPrize, $series_id)
    {
        if (!$iClassicPrize || !$series_id) {
            return false;
        } else {
            $prizeGroup = self::where('classic_prize', '=', $iClassicPrize)
                ->where('series_code', '=', $series_id)
                ->withCacheCooldownSeconds(86400)
                ->first();
            if ($prizeGroup === null) {
                return false;
            } else {
                return $prizeGroup->id;
            }
        }
    }

    /**
     * @param string $methodId
     * @param int $iClassicPrize
     * @param string $seriesCode
     * @param mixed $aPrizeSettings
     * @param mixed $aPrizeSettingOfWay
     * @param mixed $aMaxPrize
     * @return  void
     */
    public static function makePrizeSettingArray(
        $methodId,
        $iClassicPrize,
        $seriesCode,
        &$aPrizeSettings,
        &$aPrizeSettingOfWay,
        &$aMaxPrize
    ) {
        $oSeriesWay = LotterySeriesWay::getSeriesWayByMethodId($methodId, $seriesCode);
        $iPrizeGroupId = self::getPrizeGroupByClassicPrizeAndSeries($iClassicPrize, $seriesCode);
        if ($iPrizeGroupId !== null) {
            $iSeriesWayId = $oSeriesWay->id;
            if (isset($aPrizeSettings[$iSeriesWayId])) {
                $aPrizeSettingOfWay = $aPrizeSettings[$iSeriesWayId];
            } else {
                $aMethodIds = explode(',', $oSeriesWay->basic_methods);
                $aPrizeSettingOfMethods = [];
                $fMaxPrize = 0;
                foreach ($aMethodIds as $iMethodId) {
                    $aPrizeSettingOfMethods[$iMethodId] = LotteryPrizeDetail::getPrizeSetting($iPrizeGroupId, $iMethodId);
                    $fMaxPrize >= $aPrizeSettingOfMethods[$iMethodId][1] or
                    $fMaxPrize = $aPrizeSettingOfMethods[$iMethodId][1];
                }
                $aPrizeSettingOfWay = $aPrizeSettings[$iSeriesWayId] = $aPrizeSettingOfMethods;
                $aMaxPrize[$iSeriesWayId] = $fMaxPrize;
            }
        }
    }
}
