<?php

namespace App\Http\Controllers\BackendApi\Users\District;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Validator;

class RegionController extends BackEndApiMainController
{
    protected $eloqM = 'User\UsersRegion';

    //获取 省-市-县 列表
    public function detail()
    {
        $datas = $this->eloqM::whereIn('region_level', [1, 2, 3])->get()->toArray();
        return $this->msgOut(true, $datas);
    }
    //获取 镇(街道) 列表
    public function getTown()
    {
        $validator = Validator::make($this->inputs, [
            'region_parent_id' => 'required|numeric',
            'region_level' => 'required|in:3',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $check = $this->eloqM::where(['region_level' => $this->inputs['region_level'], 'region_id' => $this->inputs['region_parent_id']])->first();
        if (is_null($check)) {
            return $this->msgOut(false, [], '101000');
        }
        $datas = $this->eloqM::where(['region_level' => 4, 'region_parent_id' => $this->inputs['region_parent_id']])->get()->toArray();
        return $this->msgOut(true, $datas);
    }
    //模糊搜索 镇(街道)
    public function searchTown()
    {
        $validator = validator::make($this->inputs, [
            'search_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $datas = $this->eloqM::select('a.*', 'b.region_name as country_name', 'c.region_name as city_name', 'd.region_name as province_name')
            ->from('users_regions as a')
            ->leftJoin('users_regions as b', 'a.region_parent_id', '=', 'b.region_id')
            ->leftJoin('users_regions as c', 'b.region_parent_id', '=', 'c.region_id')
            ->leftJoin('users_regions as d', 'c.region_parent_id', '=', 'd.region_id')
            ->where([['a.region_name', 'like', '%' . $this->inputs['search_name'] . '%'], ['a.region_level', 4]])
            ->get()->toArray();
        return $this->msgOut(true, $datas);
    }
    //添加行政区
    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'region_id' => 'required|numeric',
            'region_parent_id' => 'required|numeric',
            'region_name' => 'required',
            'region_level' => 'required|in:1,2,3,4',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $addDatas = $this->inputs;
        $pastData = $this->eloqM::where(['region_parent_id' => $this->inputs['region_parent_id'], 'region_name' => $this->inputs['region_name']])->orwhere('region_id', $this->inputs['region_id'])->first();
        if (!is_null($pastData)) {
            return $this->msgOut(false, [], '101001');
        }
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
    //编辑行政区
    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:users_regions,id',
            'region_id' => 'required|numeric',
            'region_name' => 'required',
            'region_level' => 'required|in:1,2,3,4',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::where(function ($query) {
            $query->where('region_id', '=', $this->inputs['region_id'])
                ->where('id', '!=', $this->inputs['id']);
        })->first();
        if (is_null($pastData)) {
            $editDataEloq = $this->eloqM::find($this->inputs['id']);
            $editDataEloq->region_id = $this->inputs['region_id'];
            $editDataEloq->region_name = $this->inputs['region_name'];
            try {
                $editDataEloq->save();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '101001');
        }
    }
}
