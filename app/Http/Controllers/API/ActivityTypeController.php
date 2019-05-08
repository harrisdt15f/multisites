<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ActivityTypeController extends ApiMainController
{
    protected $eloqM = 'ActivityType';
    public function detail()
    {
        $datas = $this->eloqM::where('status', 1)->get()->toArray();
        return $this->msgOut(true, $datas);
    }
    /**
     * 编辑活动分类
     */
    public function editActype()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'status' => 'in:0,1',
            'l_size' => 'gt:0',
            'w_size' => 'gt:0',
            'size' => 'numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], 400, $validator->errors()->first());
        }
        $editData = $this->eloqM::find($this->inputs['id']);
        if (is_null($editData)) {
            return $this->msgOut(false, [], 100400);
        }
        unset($this->inputs['id']);
        $this->editAssignment($editData, $this->inputs);
        try {
            $editData->save();
            return $this->msgOut(true, [], '修改活动分类成功');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
