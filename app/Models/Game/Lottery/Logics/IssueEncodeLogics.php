<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/17/2019
 * Time: 9:02 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotterySeriesMethod;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

trait IssueEncodeLogics
{
    /**
     * @param $lottery_id
     * @param $issue_no
     */
    public static function calculateEncodedNumber($lottery_id, $issue_no): void
    {
        $oIssue = self::where([
            ['issue', '=', $issue_no],
            ['lottery_id', '=', $lottery_id],
        ])->first();
        if (($oIssue !== null) && $oIssue->lottery()->exists()) {
            $oLottery = $oIssue->lottery;
            if ($oLottery->serie()->exists()) {
//                $oSeries = $oLottery->serie;
                //###############################
                if ($oIssue->status_calculated === LotteryIssue::ISSUE_CODE_STATUS_FINISHED) {
                    Log::channel('issues')->info('Finished Calculating');

                } elseif ($oIssue->status_calculated === LotteryIssue::ISSUE_CODE_STATUS_CANCELED) {
                    Log::channel('issues')->info('Winning Number Canceled, Set To Finished');
                } else {
                    if ($oIssue->projects()->exists()) {
                        $oProjects = $oIssue->projects;
                        $aWnNumberOfMethods = self::getWnNumberOfSeriesMethods($oLottery,
                            $oIssue->official_code); //wn_number
                        if ($oLottery->basicways()->exists()) {
                            $oBasicWays = $oLottery->basicways;
                            foreach ($oBasicWays as $oBasicWay) {
                                $oSeriesWays = $oBasicWay->seriesWays->where('series_code',
                                    $oLottery->series_id)->where('lottery_method_id', '!=', null);
                                foreach ($oSeriesWays as $oSeriesWay) {
                                    $oSeriesWay->setWinningNumber($aWnNumberOfMethods);
                                    $oProjectsToCalculate = $oProjects->where('status',
                                        Project::STATUS_NORMAL)->where('method_sign', $oSeriesWay->lottery_method_id);
                                    if ($oProjectsToCalculate->count() >= 1) {
                                        //不中奖的时候
                                        if ($oSeriesWay->WinningNumber === false) {
                                            foreach ($oProjectsToCalculate as $project) {
                                                $project->setFail($oIssue->official_code);
                                            }
                                        } else { //中奖的时候
                                            $sWnNumber = current($oSeriesWay->WinningNumber);
                                            if ($oSeriesWay->basicWay()->exists()) {
                                                $oBasicWay = $oSeriesWay->basicWay;
                                                foreach ($oProjectsToCalculate as $project) {
                                                    $aPrized = $oBasicWay->checkPrize($oSeriesWay, $project->bet_number,
                                                        $sPostion = null);
                                                    $strlog = 'aPrized is '.json_encode($aPrized, JSON_PRETTY_PRINT);
                                                    Log::channel('issues')->info($strlog);
                                                    $result = $project->setWon($oIssue->official_code,$sWnNumber,$aPrized);//@todo Trace
                                                    if ($result !== true) {
                                                        Log::channel('issues')->info($result);
                                                    }
                                                }
                                            } else {
                                                Log::channel('issues')->info('no basic way');
                                            }
                                        }
                                    } else {
                                        Log::channel('issues')->info('Dont have projects');
                                    }
                                }
                            }
                        }

                    } else {
                        Log::channel('issues')->info('no Project');
                    }
                }
            }
        } else {
            Log::channel('issues')->info('Issue Missing');
        }
    }

    /**
     * @param $oLottery
     * @param $sFullWnNumber
     * @param  bool  $bNameKey
     * @return array
     */
    public static function getWnNumberOfSeriesMethods($oLottery, $sFullWnNumber, $bNameKey = false): array
    {
        $oSeriesMethods = LotterySeriesMethod::where('series_code', '=', $oLottery->series_id)->get();
        $aWnNumbers = array();
        $sKeyColumn = $bNameKey ? 'name' : 'id';
        foreach ($oSeriesMethods as $oSeriesMethod) {
            $aWnNumbers[$oSeriesMethod->{$sKeyColumn}] = $oSeriesMethod->getWinningNumber($sFullWnNumber);
        }
        return $aWnNumbers;
    }


}