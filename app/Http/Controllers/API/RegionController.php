<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class RegionController extends ApiMainController {
	protected $eloqM = 'Region';

	//获取 省-市-县 列表
	public function detail() {
		$datas = $this->eloqM::whereIn('region_level', [1, 2, 3])->get()->toArray();
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
			return $this->msgout(false, [], '没有获取到该信息', '0009');
		}
		return $this->msgout(true, $datas);
	}
}
