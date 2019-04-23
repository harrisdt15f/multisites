<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class RegionController extends ApiMainController {
	protected $eloqM = 'Region';

	//获取 省-市-县 列表
	public function detail() {
		$datas = $this->eloqM::whereIn('region_level', [1, 2, 3])->get()->toArray();
		if (empty($datas)) {
			return $this->msgout(false, [], '没有获取到数据', '0009');
		}
		return $this->msgout(true, $datas);
	}
	//获取 镇(街道) 列表
	public function get_town() {
		$validator = Validator::make($this->inputs, [
			'region_parent_id' => 'required|numeric',
			'region_level' => 'required|in:3',
		]);
		if ($validator->fails()) {
			return $this->msgout(false, [], $validator->errors()->first());
		}
		$check = $this->eloqM::where(['region_level' => $this->inputs['region_level'], 'region_id' => $this->inputs['region_parent_id']])->first();
		if (is_null($check)) {
			return $this->msgout(false, [], '县级行政区编码错误', '0009');
		}
		$datas = $this->eloqM::where(['region_level' => 4, 'region_parent_id' => $this->inputs['region_parent_id']])->get()->toArray();
		if (empty($datas)) {
			return $this->msgout(false, [], '没有获取到数据', '0009');
		}
		return $this->msgout(true, $datas);
	}
	//模糊搜索 镇(街道)
	public function search_town() {
		$validator = validator::make($this->inputs, [
			'search_name' => 'required',
		]);
		if ($validator->fails()) {
			return $this->msgout(false, [], $validator->errors()->first());
		}
		$datas = $this->eloqM::select('a.*', 'b.region_name as country_name', 'c.region_name as city_name', 'd.region_name as province_name')
			->from('region as a')
			->leftJoin('region as b', 'a.region_parent_id', '=', 'b.region_id')
			->leftJoin('region as c', 'b.region_parent_id', '=', 'c.region_id')
			->leftJoin('region as d', 'c.region_parent_id', '=', 'd.region_id')
			->where([['a.region_name', 'like', '%' . $this->inputs['search_name'] . '%'], ['a.region_level', 4]])
			->get()->toArray();
		if (empty($datas)) {
			return $this->msgout(false, [], '没有查询到该数据', '0009');
		}
		return $this->msgout(true, $datas);
	}
	//添加行政区
	public function add() {
		$validator = Validator::make($this->inputs, [
			'region_id' => 'required|numeric',
			'region_parent_id' => 'required|numeric',
			'region_name' => 'required',
			'region_level' => 'required|in:1,2,3,4',
		]);
		if ($validator->fails()) {
			return $this->msgout(false, [], $validator->errors()->first());
		}
		$addDatas = [
			'region_id' => $this->inputs['region_id'],
			'region_parent_id' => $this->inputs['region_parent_id'],
			'region_name' => $this->inputs['region_name'],
			'region_level' => $this->inputs['region_level'],
		];
		$pastData = $this->eloqM::where(['region_parent_id' => $this->inputs['region_parent_id'], 'region_name' => $this->inputs['region_name']])->orwhere('region_id', $this->inputs['region_id'])->first();
		if (is_null($pastData)) {
			try {
				$configure = new $this->eloqM();
				$configure->fill($addDatas);
				$configure->save();
				return $this->msgout(true, [], '添加行政区成功');
			} catch (\Exception $e) {
				$errorObj = $e->getPrevious()->getPrevious();
				[$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
				return $this->msgout(false, [], $msg, $sqlState);
			}
		} else {
			return $this->msgout(false, [], '该行政区已经存在', '0009');
		}
	}
	//编辑行政区
	public function edit() {
		$validator = Validator::make($this->inputs, [
			'id' => 'required|numeric',
			'region_id' => 'required|numeric',
			'region_name' => 'required',
			'region_level' => 'required|in:1,2,3,4',
		]);
		if ($validator->fails()) {
			return $this->msgout(false, [], $validator->errors()->first(), 200);
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
				return $this->msgout(true, [], '修改行政区成功');
			} catch (\Exception $e) {
				$errorObj = $e->getPrevious()->getPrevious();
				[$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
				return $this->msgout(false, [], $msg, $sqlState);
			}
		} else {
			return $this->msgout(false, [], '该行政区ID已存在', '0009');
		}
	}
}
