<?php

namespace App\Http\Controllers\BackendApi;

use App\Events\IssueGenerateEvent;
use App\models\LotteriesModel;
use App\models\MethodsModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'LotteriesModel';

    public function seriesLists()
    {
        $seriesData = Config::get('game.main.series');
        return $this->msgOut(true, $seriesData);
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
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $lotteriesEloq = $this->eloqM::where([
            ['series_id', '=', $this->inputs['series_id']],
            ['status', '=', 1],
        ])->with([
            'issueRule' => function ($query) {
                $query->select('id', 'lottery_id', 'lottery_name', 'begin_time', 'end_time', 'issue_seconds',
                    'first_time', 'adjust_time', 'encode_time', 'issue_count', 'status', 'created_at', 'updated_at');
            },
        ])->get()->toArray();
        return $this->msgOut(true, $lotteriesEloq);
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
                $lotteriesIds = $methodEloq->lotteriesIds; //        dd($lotteriesIds);
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
        return $this->msgOut(true, $method);
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
        return $this->msgOut(true, $data);
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
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        event(new IssueGenerateEvent($this->inputs));
        return $this->msgOut(true);
    }
}
