<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/17/2019
 * Time: 9:02 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotterySerie;
use App\Models\Game\Lottery\LotterySeriesMethod;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait IssueEncodeLogics
{
    public static function calculateEncodedNumber($lottery_id, $issue_no)
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
                    Log::info('Finished Calculating');

                } elseif ($oIssue->status_calculated === LotteryIssue::ISSUE_CODE_STATUS_CANCELED) {
                    Log::info('Winning Number Canceled, Set To Finished');
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
                                                try {
                                                    DB::beginTransaction();
                                                    $lockProject = Project::where('id',
                                                        $project->id)->lockForUpdate()->first();
                                                    $project->status = $lockProject->status = Project::STATUS_LOST;
                                                    $lockProject->save();
                                                } catch (\Exception $e) {
                                                    DB::rollBack();
                                                }
                                                DB::commit();
                                            }
                                        } else { //中奖的时候
                                            if ($oSeriesWay->basicWay()->exists()) {
                                                $oBasicWay = $oSeriesWay->basicWay;
                                                foreach ($oProjectsToCalculate as $project) {
                                                    $aPrized = $oBasicWay->checkPrize($oSeriesWay, $project->bet_number,
                                                        $sPostion = null);
                                                    $project->setWon($oIssue->wn_number, $aPrized);//@todo Trace
                                                }
                                            } else {
                                                Log::info('no basic way');
                                            }
                                        }
                                    } else {
                                        Log::info('Dont have projects');
                                    }

                                }
                            }
                        }

                    } else {
                        Log::info('no Project');
                    }
                }
            }
        } else {
            Log::info('Issue Missing');
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

    public static function calculateProjectsOfWay($oSeriesWay, $oIssue, &$aResult)
    {
        if ($oSeriesWay->WinningNumber === false) {
            $sMsg = 'Batch set unprizedProjcts ';
            DB::beginTransaction();
            if (!($bSucc = ManProject::setLostOfWay($oIssue->wn_number, $oIssue->lottery_id, $oIssue->issue,
                $oSeriesWay->id))) {
                DB::rollback();
                $sMsg .= 'Failed';
            } else {
                DB::commit();
//                $this->setTraceTaskOfWay($oSeriesWay, $oIssue);
                $sMsg .= 'Success';
            }
            $aResult = array($aTotalCount, 0, $aTotalCount, $sMsg);
            return $bSucc;
        }

    }


}