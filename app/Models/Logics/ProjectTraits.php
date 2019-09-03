<?php

namespace App\Models\Logics;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryPrizeGroup;
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\LotteryTrace;
use App\Models\User\FrontendUser;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

trait ProjectTraits
{
    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param  LotteryIssue  $currentIssue
     * @param  array  $data
     * @param  array  $inputDatas
     * @param  int  $from  手机端 还是 pc 端
     * @return array
     */
    public static function addProject(
        FrontendUser $user,
        LotteryList $lottery,
        LotteryIssue $currentIssue,
        $data,
        array $inputDatas,
        $from = 1
    ): array {
        $traceFirstMultiple = 1;
        $isTrace = 0;
        $traceData = [];
        $returnData = [];
        $traceResult = self::traceCompile($lottery, $currentIssue, $inputDatas, $traceFirstMultiple, $traceData);
        if (isset($traceResult['error'])) {
            return $traceResult;
        } else {
            foreach ($data as $_item) {
                $project = self::saveSingleProject(
                    $user,
                    $lottery,
                    $_item,
                    $inputDatas,
                    $isTrace,
                    $traceFirstMultiple,
                    $currentIssue,
                    $from
                );
                if ($traceData) {
                    self::saveTrace($project, $user, $lottery, $data, $traceData, $_item, $inputDatas, $from);
                }
                $returnData['project'][] = [
                    'id' => $project->id,
                    'cost' => $_item['total_price'],
                    'lottery_id' => $lottery->en_name,
                    'method_id' => $_item['method_id'],
                ];
            }
            return $returnData;
        }
    }

    /**
     * @param  LotteryList  $lottery
     * @param  LotteryIssue  $currentIssue
     * @param  array  $inputDatas
     * @param $traceFirstMultiple
     * @param $traceData
     * @return mixed
     */
    public static function traceCompile(
        LotteryList $lottery,
        LotteryIssue $currentIssue,
        array $inputDatas,
        &$traceFirstMultiple,
        &$traceData
    ) {
        $isTrace = (int)$inputDatas['is_trace'];
        if ($isTrace === 1 && count($inputDatas['trace_issues']) > 1) {
            // 追号期号
            $arrTraceKeys = array_keys($inputDatas['trace_issues']);
            $traceDataCollection = $lottery->checkTraceData($arrTraceKeys);
            $traceFirstMultiple = Arr::first($inputDatas['trace_issues']);
            // $traceData = array_slice($inputDatas['trace_issues'], 1, null, true);
            $traceData = $inputDatas['trace_issues'];
            if (count($arrTraceKeys) !== $traceDataCollection->count()) {
                $traceError['error'] = '100309';
                return $traceError;
            }
        } elseif ($isTrace === 0) {
            // 投注期号是否正确
            if ($currentIssue->issue !== (string)key($inputDatas['trace_issues'])) {
                $traceError['error'] = '100310';
                return $traceError;
            }
        }
    }

    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param  array  $_item
     * @param  array  $inputDatas
     * @param  int  $isTrace
     * @param  int  $traceFirstMultiple
     * @param  LotteryIssue  $currentIssue
     * @param  int  $from
     * @return mixed
     */
    public static function saveSingleProject(
        FrontendUser $user,
        LotteryList $lottery,
        $_item,
        array $inputDatas,
        $isTrace,
        $traceFirstMultiple,
        LotteryIssue $currentIssue,
        $from
    ) {
        if ($lottery->serie()->exists()) {
            $subTractPrize = $lottery->serie->price_difference;
        } else {
            $subTractPrize = 0;
        }
        $bresult = LotteryPrizeGroup::makePrizeSettingArray(
            $_item['method_id'],
            self::DEFAULT_PRIZE_GROUP,
            $lottery->series_id,
            $aPrizeSettings,
            $aPrizeSettingOfWay,
            $aMaxPrize
        );
        if ($bresult) {
            die('奖金组错误');
        }
        $projectData = [
            'serial_number' => self::getProjectSerialNumber(),
            'user_id' => $user->id,
            'username' => $user->username,
            'top_id' => $user->top_id,
            'rid' => $user->rid,
            'parent_id' => $user->parent_id,
            'is_tester' => $user->is_tester,
            'series_id' => $lottery->series_id,
            'lottery_sign' => $lottery->en_name,
            'method_sign' => $_item['method_id'],
            'method_name' => $_item['method_name'],
            'method_group' => $_item['method_group'],
            'user_prize_group' => $user->prize_group,
            'bet_prize_group' => $_item['prize_group'] - $subTractPrize,
            'prize_set' => json_encode($aPrizeSettingOfWay),
            'mode' => $_item['mode'],
            'times' => $isTrace === 1 && count(
                $inputDatas['trace_issues']
            ) > 0 ? $_item['times'] * $traceFirstMultiple : $_item['times'],
            'price' => $isTrace === 1 && count(
                $inputDatas['trace_issues']
            ) > 0 ? $_item['price'] * $traceFirstMultiple : $_item['price'],
            'total_cost' => $_item['total_price'],
            'bet_number' => $_item['code'],
            'issue' => $currentIssue->issue,
            'ip' => Request::ip(),
            'proxy_ip' => json_encode(Request::ip()),
            'bet_from' => $from,
            'time_bought' => time(),
        ];
        return self::create($projectData);
    }

    /**
     * @return string
     */
    public static function getProjectSerialNumber(): string
    {
        return 'XW'.Str::orderedUuid()->getNodeHex();
    }

    /**
     * @param  mixed  $project
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param  array  $data
     * @param  array  $traceData
     * @param  array  $_item
     * @param  array  $inputDatas
     * @param  int  $from
     */
    public static function saveTrace(
        $project,
        FrontendUser $user,
        LotteryList $lottery,
        $data,
        $traceData,
        $_item,
        $inputDatas,
        $from
    ): void {
        LotteryPrizeGroup::makePrizeSettingArray(
            $_item['method_id'],
            self::DEFAULT_PRIZE_GROUP,
            $lottery->series_id,
            $aPrizeSettings,
            $aPrizeSettingOfWay,
            $aMaxPrize
        );
        // 保存主追号
        $traceId = LotteryTrace::createTraceData(
            $user,
            $project,
            $lottery,
            $traceData,
            $_item,
            $aPrizeSettingOfWay,
            $inputDatas,
            $from
        );
        // 保存追号列表
        LotteryTraceList::createTraceListData(
            $traceId,
            $traceData,
            $data,
            $project,
            $user,
            $lottery,
            $_item,
            $aPrizeSettingOfWay,
            $from
        );
    }

    public function setWon(
        $openNumber,
        $sWnNumber,
        $aPrized
    ) {
        $this->compileBonus($aPrized, $sWnNumber, $totalBonus, $totalCount, $arrLevel, $arrBasicMethodId);
        if ($totalCount > 0) {
            $data = [
                'basic_method_id' => implode(',', $arrBasicMethodId),
                'open_number' => $openNumber,
                'winning_number' => $this->formatWiningNumber($sWnNumber),
                'level' => implode(',', $arrLevel), //@todo may be with string to concact
                'bonus' => $totalBonus,
                'is_win' => 1,
                'time_count' => now()->timestamp,
                'status' => self::STATUS_WON,
            ];
            try {
                DB::beginTransaction();
                $this->update($data); //@todo maybe only a time update
                DB::commit();
                $this->sendMoney();
                return true;
            } catch (Exception $e) {
                Log::channel('issues')->info($e->getMessage());
                DB::rollBack();
                return $e->getMessage();
            }
        } else {
            return $this->setFail($openNumber, $sWnNumber);
        }
    }

    /**
     * @param $aPrized
     * @param $sWnNumber
     * @param $totalBonus
     * @param $totalCount
     * @param $arrLevel
     * @param $arrBasicMethodId
     */
    private function compileBonus(
        $aPrized,
        $sWnNumber,
        &$totalBonus,
        &$totalCount,
        &$arrLevel,
        &$arrBasicMethodId
    ): void {
        $arrBasicMethodId = [];
        $arrLevel = [];
        $totalBonus = 0;
        $totalCount = 0;
        foreach ($aPrized as $iBasicMethodId => $aPrizeOfBasicMethod) {
            $this->calculateEachPrizeSetting(
                $aPrizeOfBasicMethod,
                $iBasicMethodId,
                $sWnNumber,
                $totalBonus,
                $totalCount,
                $arrLevel,
                $arrBasicMethodId
            );
        }
    }

    /**
     * @param $aPrizeOfBasicMethod
     * @param $iBasicMethodId
     * @param $sWnNumber
     * @param $totalBonus
     * @param $totalCount
     * @param $arrLevel
     * @param $arrBasicMethodId
     */
    private function calculateEachPrizeSetting(
        $aPrizeOfBasicMethod,
        $iBasicMethodId,
        $sWnNumber,
        &$totalBonus,
        &$totalCount,
        &$arrLevel,
        &$arrBasicMethodId
    ): void {
        $aPrizeSet = json_decode($this->prize_set, true);
        foreach ($aPrizeOfBasicMethod as $iLevel => $iCount) {
            $prizeToClaim = $this->getPrizeToClaim($iBasicMethodId, $sWnNumber, $aPrizeSet, $iLevel);
            if ($prizeToClaim !== null) {
                if ($iCount > 0) {
                    $this->calculateBonus($prizeToClaim, $iCount, $totalCount, $totalBonus);
                    $arrLevel[] = $iLevel;
                    $arrBasicMethodId[] = $iBasicMethodId;
                } else {
                    $errorString = 'There have no Count:'.$iBasicMethodId.' level:'.$iLevel.' Count:'.$iCount;
                    Log::channel('issues')->info($errorString);
                }
            } else {
                $levelDataNote = 'leveldata'.json_encode($aPrizeOfBasicMethod);
                $errorString = 'There have no prize for  Basic MethodId'.$iBasicMethodId.$levelDataNote;
                Log::channel('issues')->error($errorString);
            }
        }
    }

    /**
     * @param $prizeToClaim
     * @param $iCount
     * @param $totalCount
     * @param $totalBonus
     */
    private function calculateBonus($prizeToClaim, $iCount, &$totalCount, &$totalBonus): void
    {
        $bonus = $this->bet_prize_group * $prizeToClaim / 1800;
        $bonus *= $this->mode * $this->times * $iCount;
        if (pack('f', $this->price) === pack('f', 1.0)) {
            $bonus /= 2;
        }
        $totalCount += $iCount;
        $totalBonus += $bonus;
    }

    /**
     * @param $iBasicMethodId
     * @param $sWnNumber
     * @param $aPrizeSet
     * @param $iLevel
     * @return int|mixed
     */
    private function getPrizeToClaim($iBasicMethodId, $sWnNumber, $aPrizeSet, $iLevel)
    {
        if ($iBasicMethodId === 123) {
            $winExplodedNum = explode(' ', $sWnNumber);
            $tema = end($winExplodedNum);
            if ($tema === '49') {
                $prizeToClaim = 1;
            } else {
                $prizeToClaim = $aPrizeSet[$iBasicMethodId][$iLevel];
            }
        } else {
            $prizeToClaim = $aPrizeSet[$iBasicMethodId][$iLevel];
        }
        return $prizeToClaim;
    }

    /**
     * @param  mixed  $sWnNumber
     * @return string|null
     */
    private function formatWiningNumber(
        $sWnNumber = null
    ): ?string {
        return is_array($sWnNumber) ? implode('', $sWnNumber) : $sWnNumber;
    }

    public function sendMoney(): void
    {
        $params = [
            'amount' => $this->bonus,
            'frozen_release' => $this->total_cost,
            'user_id' => $this->user_id,
            'project_id' => $this->id,
            'lottery_id' => $this->lottery_sign,
            'method_id' => $this->method_sign,
            'issue' => $this->issue,
        ];
        $account = $this->account;
        DB::beginTransaction();
        try {
            $result = $account->operateAccount($params, 'game_bonus');
            if ($result !== true) {
                Log::info($result);
            } else {
                $result = $this->updateStatusPrizeSend();
            }
        } catch (Exception $e) {
            $result = false;
            $info = '投注-异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine();
            Log::channel('calculate-prize')->info($info); //Clog::userBet
        }
        if ($result === true) {
            DB::commit();
        } else {
            DB::rollBack();
        }
    }

    /**
     * @return bool
     */
    private function updateStatusPrizeSend(): bool
    {
        $oProject = self::find($this->id);
        if ($oProject->status === self::STATUS_WON) {
            $oProject->status = self::STATUS_PRIZE_SENT;
            $oProject->time_prize = now()->timestamp;
            $oProject->save();
            if (!empty($this->errors()->first())) {
                $result = false;
                $info = '更新状态出错'.json_encode($this->errors()->first(), JSON_PRETTY_PRINT);
                Log::channel('calculate-prize')->info($info);
            } else {
                $result = true;
                Log::channel('calculate-prize')->info('Finished Send Money with bonus');
            }
        } else {
            $result = true;
            Log::channel('calculate-prize')->info('Finished Send Money with release frozen');
        }
        return $result;
    }

    /**
     * @param $openNumber
     * @param $sWnNumber
     * @return bool
     */
    public function setFail(
        $openNumber,
        $sWnNumber = null
    ): bool {
        try {
            DB::beginTransaction();
//            $lockProject = $this->lockForUpdate()->find($this->id);
            $this->status = self::STATUS_LOST;
            $data = [
//                'basic_method_id' => $iBasicMethodId,
                'open_number' => $openNumber,
                'winning_number' => $this->formatWiningNumber($sWnNumber),
                'time_count' => now()->timestamp,
                'status' => self::STATUS_LOST,
            ];
            if ($this->update($data)) {
                DB::commit();
                // $this->sendMoney();
            } else {
                $strError = json_encode($this->errors()->first(), JSON_PRETTY_PRINT);
                Log::channel('issues')->info($strError);
            }
        } catch (Exception $e) {
            Log::channel('issues')->info($e->getMessage());
            DB::rollBack();
            return false;
        }
        return true;
    }
}
