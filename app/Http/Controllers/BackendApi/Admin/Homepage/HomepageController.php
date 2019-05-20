<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Common\Image;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Validator;

class HomepageController extends BackEndApiMainController
{
    protected $eloqM = 'HomepageModel';

    public function detail()
    {
        $validator = Validator::make($this->inputs, [
            'pid' => 'required|numeric|in:1,2,3,4,5,6',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $datas = $this->eloqM::where('pid', $this->inputs['pid'])->get();
        return $this->msgOut(true, $datas);
    }

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'status' => 'numeric|in:0,1',
            'value' => 'string',
            'show_num' => 'numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101900');
        }
        if (array_key_exists('status', $this->inputs)) {
            $pastData->status = $this->inputs['status'];
        }
        if (array_key_exists('value', $this->inputs)) {
            if ($pastData->is_edit_value === 1) {
                $pastData->value = $this->inputs['value'];
            } else {
                return $this->msgOut(false, [], '101901');
            }
        }
        if (array_key_exists('show_num', $this->inputs)) {
            if ($pastData->is_edit_show_num === 1) {
                $pastData->show_num = $this->inputs['show_num'];
            } else {
                return $this->msgOut(false, [], '101902');
            }
        }
        try {
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function uploadLogo()
    {
        $validator = Validator::make($this->inputs, [
            'pic' => 'required|image',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('key', 'logo')->first();
        if (is_null($pastData)) {
            return $this->msgOut(false, [], '101903');
        }
        //上传图片
        $imgClass = new Image();
        $depositPath = $imgClass->depositPath('Logo', $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imgClass->uploadImg($this->inputs['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '101904');
        }
        try {
            $pastData->value = '/' . $pic['path'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $imgClass->deletePic($pic['path']);
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
