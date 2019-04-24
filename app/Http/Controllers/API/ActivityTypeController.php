<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ActivityTypeController extends ApiMainController {
	protected $eloqM = 'ActivityType';
	public function detail() {
		$datas = $this->eloqM::where('status', 1)->get()->toArray();
		if (empty($datas)) {
			return $this->msgout(false, [], '没有获取到数据', '0009');
		}
		return $this->msgout(true, $datas);
	}
}