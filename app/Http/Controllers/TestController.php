<?php

namespace App\Http\Controllers;
use App\Http\Controllers\ApiMainController;
use DB;

class TestController extends ApiMainController {
	protected $eloqM = 'PartnerSysConfigures';
	public function test() {

		// $aa = $this->eloqM::select('select * from PartnerSysConfigures as a leftjoin PartnerSysConfigures as b on a.id=b.parent_id where a.parent = 0', [1]);
		$aa = DB::table('partner_sys_configures as a')->leftjoin('partner_sys_configures as b', 'partner_sys_configures.id', '=', 'b.parent_id')->where('partner_sys_configures.parent_id', '=', 0)->get()->toArray();
		dd($aa);
// 		$json = '

// ';
		// 		$jsonData = json_decode($json, true);
		// 		foreach ($jsonData as $k => $v) {
		// 			foreach ($jsonData[$k] as $k2 => $v2) {
		// 				$addData['region_id'] = $jsonData[$k][$k2]['id'];
		// 				$addData['region_parent_id'] = $k;
		// 				$addData['region_name'] = $jsonData[$k][$k2]['name'];
		// 				$addData['region_level'] = 4;
		// 				$addDatas[] = $addData;
		// 			}
		// 		}
		// 		// dd($addDatas);exit;
		// 		try {
		// 			$this->eloqM::insert($addDatas);
		// 			// dd($addDatas);
		// 		} catch (\Exception $e) {
		// 			echo 'shibai';
		// 		}
	}
}
