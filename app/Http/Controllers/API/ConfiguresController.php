<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\ApiMainController;
use App\models\PartnerSysConfigures;

class ConfiguresController extends ApiMainController
{
    public function getConfiguresList()
    {
    	$data = $this->getSub(0);
    	foreach ($data as $k => $v) {
    		$sub = $this->getSub($data[$k]['id']);
    		$data[$k]['sub'] = $sub;
    	}
    	// dd($data);
        return $this->msgout(true, $data);
    }
    public function getSub($parent_id){
    	$data = PartnerSysConfigures::where('parent_id','=',$parent_id)->get()->toArray();
    	return $data;
    }
}
