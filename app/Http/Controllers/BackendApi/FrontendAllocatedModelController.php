<?php

namespace App\Http\Controllers\BackendApi;

use Illuminate\Support\Facades\Validator;

class FrontendAllocatedModelController extends BackEndApiMainController
{
    protected $eloqM = 'FrontendAllocatedModel';

    public function detail()
    {
        $datas = $this->eloqM::get();
        return $this->msgOut(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'label' => 'required|string',
            'en_name' => 'required|string',
            'pid' => 'required|numeric',
            'type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkLabelEloq = $this->eloqM::where('label', $this->inputs['label'])->first();
        if (!is_null($checkLabelEloq)) {
            return $this->msgOut(false, [], '101600');
        }
        $checkEnNamelEloq = $this->eloqM::where('en_name', $this->inputs['en_name'])->first();
        if (!is_null($checkEnNamelEloq)) {
            return $this->msgOut(false, [], '101601');
        }
        try {
            $modelEloq = new $this->eloqM;
            $modelEloq->fill($this->inputs);
            $modelEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkidIdEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($checkidIdEloq)) {
            return $this->msgOut(false, [], '101602');
        }
        try {
            $this->eloqM::where('id', $this->inputs['id'])->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
