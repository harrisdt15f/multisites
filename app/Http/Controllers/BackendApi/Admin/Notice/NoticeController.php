<?php

namespace App\Http\Controllers\BackendApi\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NoticeController extends BackEndApiMainController
{
    protected $eloqM = 'Notice';

    public function detail()
    {
        $datas = $this->eloqM::select('id', 'type', 'title', 'content', 'start_time', 'end_time', 'sort', 'status', 'admin_id')->orderBy('sort', 'asc')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'type' => 'required|numeric',
            'title' => 'required|string',
            'content' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkTitle = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '102100');
        }
        $addData = $this->inputs;
        //admin_id
        $addData['admin_id'] = $this->partnerAdmin->id;
        //sort
        $sortdata = $this->eloqM::orderBy('sort', 'desc')->first();
        if (is_null($sortdata)) {
            $addData['sort'] = 1;
        } else {
            $addData['sort'] = $sortdata['sort'] + 1;
        }
        try {
            $noticeEloq = new $this->eloqM;
            $noticeEloq->fill($addData);
            $noticeEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'type' => 'required|numeric',
            'title' => 'required|string',
            'content' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgOut(false, [], '102101');
        }
        $checkTitle = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '102100');
        }
        try {
            $this->editAssignment($pastEloq, $this->inputs);
            $pastEloq->save();
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
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgOut(false, [], '102101');
        }
        //sort
        $sort = $pastEloq->sort;
        DB::beginTransaction();
        try {
            $pastEloq->delete();
            $this->eloqM::where('sort', '>', $sort)->decrement('sort');
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
