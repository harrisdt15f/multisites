<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\MethodsModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends ApiMainController
{
    protected $eloqM = 'LotteriesModel';

    public function seriesLists()
    {
        $seriesData = Config::get('game.main.series');
        return $this->msgout(true,$seriesData);
    }

    public function lotteriesLists()
    {
        $series = array_keys(Config::get('game.main.series'));
        $seriesStringImploded = implode(',', $series);
        $rule = [
            'series_id' => 'required|in:'.$seriesStringImploded,
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 401);
        }
        $lotteriesEloq = $this->eloqM::where([
            ['series_id', '=', $this->inputs['series_id']],
            ['status', '=', 1],
        ])->get()->toArray();
        return $this->msgout(true,$lotteriesEloq);
    }

    public function lotteriesMethodLists()
    {
        $methodEloq = MethodsModel::where([
            ['series_id', '=', 'ssc'],
        ])->first();
        $method =[];
        $m[$methodEloq->series_id] =$methodEloq->lotteriesIds->toArray();
        return $m;
    }


}
