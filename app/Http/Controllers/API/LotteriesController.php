<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\LotteriesModel;
use App\models\MethodsModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends ApiMainController
{
    protected $eloqM = 'LotteriesModel';

    public function seriesLists()
    {
        $seriesData = Config::get('game.main.series');
        return $this->msgout(true, $seriesData);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lotteriesLists()
    {
        $series = array_keys(Config::get('game.main.series'));
        $seriesStringImploded = implode(',', $series);
        $rule = [
            'series_id' => 'required|in:' . $seriesStringImploded,
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 401);
        }
        $lotteriesEloq = $this->eloqM::where([
            ['series_id', '=', $this->inputs['series_id']],
            ['status', '=', 1],
        ])->with(['issueRule' => function ($query) {
            $query->select('id', 'lottery_id', 'lottery_name', 'begin_time', 'end_time', 'issue_seconds', 'first_time', 'adjust_time', 'encode_time', 'issue_count', 'status', 'created_at', 'updated_at');
        }])->get()->toArray();
        return $this->msgout(true, $lotteriesEloq);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lotteriesMethodLists()
    {
        $method = [];
        $redisKey = 'play_method_list';
        if (Cache::has($redisKey)) {
            $method = Cache::get($redisKey);
        } else {
            $seriesList = array_keys(Config::get('game.main.series'));
            foreach ($seriesList as $seriesIthem) {
                $methodEloq = MethodsModel::where([
                    ['series_id', '=', $seriesIthem],
                ])->first();
                $lotteriesIds = $methodEloq->lotteriesIds;//        dd($lotteriesIds);
                foreach ($lotteriesIds as $litems) {
                    $currentLotteryId = $litems->lottery_id;
                    $temp[$seriesIthem][$currentLotteryId] = [];
                    $methodGrops = $litems->methodGroups;
                    foreach ($methodGrops as $mgitems) {
                        $curentMethodGroup = $mgitems->method_group;
                        $temp[$seriesIthem][$currentLotteryId][$curentMethodGroup] = [];
                        $methodRows = $mgitems->methodRows;
                        foreach ($methodRows as $rowitems) {
                            $temp[$seriesIthem][$currentLotteryId][$curentMethodGroup][$rowitems->method_row] = $rowitems->methodDetails->toArray();
                        }
                    }

                }
                $method = array_merge($method, $temp);
            }
            $hourToStore = 24;
            $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
            Cache::put($redisKey, $method, $expiresAt);
//            Cache::forever($redisKey, $method);
        }
        return $this->msgout(true, $method);
    }

    public function lotteryIssueLists()
    {
        $modelName = 'IssueModel';
        $eloqM = $this->modelWithNameSpace($modelName);
        $seriesId = $this->inputs['series_id'] ?? '';
//        {"method":"whereIn","key":"id","value":["cqssc","xjssc","hljssc","zx1fc","txffc"]}
//        $extraWhereConditions = Arr::wrap(json_decode($this->inputs['extra_where'], true));
        if (!empty($seriesId)) {
            $lotteryEnNames = LotteriesModel::where('series_id', $seriesId)->get(['en_name']);
            foreach ($lotteryEnNames as $lotteryIthems) {
                $tempLotteryId[] = $lotteryIthems->en_name;
            }
            $this->inputs['extra_where']['method'] = 'whereIn';
            $this->inputs['extra_where']['key'] = 'lottery_id';
            $this->inputs['extra_where']['value'] = $tempLotteryId;
        }
        $searchAbleFields = ['lottery_id', 'issue'];
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields);
        return $this->msgout(true, $data);
    }

    // 生成奖期
    public function generateIssue()
    {
        $rule = [
            'lottery_id' => 'required',
            'start_time' => 'required|date_format:Y-m-d',
            'end_time' => 'required|date_format:Y-m-d',
//            'start_issue' => 'required|numeric',//
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 200);
        }
        $lotteryId = $this->inputs['lottery_id'];
        $lottery = LotteriesModel::where('en_name', $lotteryId)->first();
        if (!$lottery) {
            return $this->msgout(false, [], '游戏不存在!', '0002');
        }
        // 生成
        $res = $lottery->genIssue($this->inputs['start_time'], $this->inputs['end_time'], $this->inputs['start_issue']);
        if (!is_array($res) || count($res) === 0) {
            return $this->msgout(false, [], '', '0002');
        } else {
            // 成功一部分
            $genRes = true;
            foreach ($res as $day => $_r) {
                if ($_r !== true) {
                    $genRes = false;
                    $message = $_r;
                }
            }
            if (!$genRes) {
                return $this->msgout(false, [], $message, '0002');
            } else {
                return $this->msgout(true, $res);
            }
        }
    }
}
