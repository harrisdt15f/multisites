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
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\LotteryTrace;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

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
                                                self::startTrace($oLottery, $project);
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
                                                    $win = 0;
                                                    $result = $project->setWon($oIssue->official_code, $sWnNumber,
                                                        $aPrized, $win);//@todo Trace
                                                    if ($result !== true) {
                                                        Log::channel('issues')->info($result);
                                                    }
                                                    self::startTrace($oLottery, $project);
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

    /**
     * @param $oLottery
     * @param $project
     */
    public static function startTrace($oLottery, $project): void
    {
        $oProject = $project->fresh();
        $first = 0;
        $oTrace = LotteryTrace::where([
            ['user_id', '=', $oProject->user_id],
            ['lottery_sign', '=', $oProject->lottery_sign],
            ['now_issue', '=', $oProject->issue],
            ['bet_number', '=', $oProject->bet_number]
        ])->first();
        if ($oTrace === null) {
            $oTrace = LotteryTrace::where([
                ['user_id', '=', $oProject->user_id],
                ['lottery_sign', '=', $oProject->lottery_sign],
                ['bet_number', '=', $oProject->bet_number]
            ])->first();
            ++$first;
        }
        if ($oTrace !== null) {
            if ($oProject->status >= 3 && $oTrace->win_stop === 1) {
                //Remaining TraceList to stop continuing
                $oTraceListToUpdate = $oTrace->traceRunningLists();
                $traceListStopData = [
                    'status' => LotteryTraceList::STATUS_USER_STOPED,
                ];
                $oTraceListToUpdate->update($traceListStopData);
                //Update TraceDetail tables
                $oTrace->status = LotteryTrace::STATUS_USER_CANCELED;
                $oTrace->canceled_issues = $oTraceListToUpdate->count();
                $oTrace->canceled_amount = $oTraceListToUpdate->sum('total_price');
                $oTrace->stop_issue = $oProject->issue;
                $oTrace->stop_time = time();
                $oTrace->save();
                //update TraceLists with Project
                if ($oProject->tracelist()->exists())//第一次的时候是没有的
                {
                    $oTraceListFromProject = $oProject->tracelist;
                    $oTraceListFromProject->status = LotteryTraceList::STATUS_FINISHED;
                    $oTraceListFromProject->save();
                }
            } elseif ($oProject->status >= 3 && $first < 1) {//不是第一次的时候
                ++$oTrace->finished_issues;
                $oTrace->finished_amount += $oProject->total_cost;
                $oTrace->finished_bonus += $oProject->bonus;
                if ($oTrace->end_issue === $oProject->issue) {
                    $oTrace->status = LotteryTrace::STATUS_FINISHED;
                }
                $oTrace->save();
                //update TraceLists with Project
                if ($oProject->tracelist()->exists())//第一次的时候是没有的
                {
                    $oTraceListFromProject = $oProject->tracelist;
                    $oTraceListFromProject->status = LotteryTraceList::STATUS_FINISHED;
                    $oTraceListFromProject->save();
                }
            }
        }
        //get current issues
        $currentIssue = LotteryIssue::getCurrentIssue($oLottery->en_name);
        //then check if there have tracelists or not
        if ($currentIssue->tracelists()->exists()) {
            //select with criterias
            $oTraceListEloq = $currentIssue->tracelists()->where('lottery_sign',
                $oLottery->en_name)->get();
            //check if it is not empty then do other logics
            if (!empty($oTraceListEloq->toArray())) {
                //loop ,select and then insert to project table and update the trace detail table
                foreach ($oTraceListEloq as $oTraceList) {
                    if ($oTraceList->trace()->exists()) {
                        $oTrace = $oTraceList->trace;
                        if ($oTraceList->status === LotteryTraceList::STATUS_RUNNING) {//停止了就不加追号了
                            //添加到 project 表
                            $projectData = [
                                'user_id' => $oTraceList->user_id,
                                'username' => $oTraceList->username,
                                'top_id' => $oTraceList->top_id,
                                'rid' => $oTraceList->rid,
                                'parent_id' => $oTraceList->parent_id,
                                'is_tester' => $oTraceList->is_tester,
                                'series_id' => $oTraceList->series_id,
                                'lottery_sign' => $oTraceList->lottery_sign,
                                'method_sign' => $oTraceList->method_sign,
                                'method_name' => $oTraceList->method_name,
                                'user_prize_group' => $oTraceList->user_prize_group,
                                'bet_prize_group' => $oTraceList->bet_prize_group,
                                'mode' => $oTraceList->mode,
                                'times' => $oTraceList->times,
                                'price' => $oTraceList->single_price,
                                'total_cost' => $oTraceList->total_price,
                                'bet_number' => $oTraceList->bet_number,
                                'issue' => $oTraceList->issue,
                                'prize_set' => '',
                                'ip' => $oTraceList->ip,
                                'proxy_ip' => $oTraceList->proxy_ip,
                                'bet_from' => $oTraceList->bet_from,
                                'time_bought' => time(),
                            ];
                            $projectId = Project::create($projectData)->id;
                            $oTraceList->project_id = $projectId;
                            $oTraceList->save();
                            $TraceDetailUpdateData = [
                                'now_issue' => $oTraceList->issue,
                            ];
                            $oTrace->update($TraceDetailUpdateData);
                        }
                        //##############################################################
                    } else {
                        Log::channel('issues')->info('追号统计列表信息失踪');
                    }
                }
            }

        }
    }


}