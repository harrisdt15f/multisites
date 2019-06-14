<?php

namespace App\Http\Controllers\BackendApi\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Notice\NoticeAddRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeDeleteRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeEditRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeSortRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeTopRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NoticeController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Notice\FrontendMessageNotice';

    //公告列表
    public function detail(): JsonResponse
    {
        $noticeDatas = $this->eloqM::select('id', 'type', 'title', 'content', 'start_time', 'end_time', 'sort', 'status', 'admin_id')->with('admin')->orderBy('sort', 'asc')->get()->toArray();
        foreach ($noticeDatas as $key => $data) {
            $noticeDatas[$key]['admin_name'] = $data['admin']['name'];
            unset($noticeDatas[$key]['admin_id']);
            unset($noticeDatas[$key]['admin']);
        }
        return $this->msgOut(true, $noticeDatas);
    }

    //添加公告
    public function add(NoticeAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $addData = $inputDatas;
        //admin_id
        $addData['admin_id'] = $this->partnerAdmin->id;
        //sort
        $maxSort = $this->eloqM::max('sort');
        $addData['sort'] = is_null($maxSort) ? 1 : $maxSort++;
        try {
            $noticeEloq = new $this->eloqM;
            $noticeEloq->fill($addData);
            $noticeEloq->save();
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑公告
    public function edit(NoticeEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        $checkTitle = $this->eloqM::where('title', $inputDatas['title'])->where('id', '!=', $inputDatas['id'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '102100');
        }
        try {
            $this->editAssignment($pastEloq, $inputDatas);
            $pastEloq->save();
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除公告
    public function delete(NoticeDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        //sort
        $sort = $pastEloq->sort;
        DB::beginTransaction();
        try {
            $pastEloq->delete();
            $this->eloqM::where('sort', '>', $sort)->decrement('sort');
            DB::commit();
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //公告排序
    public function sort(NoticeSortRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        DB::beginTransaction();
        try {
            //上拉排序
            if ($inputDatas['sort_type'] == 1) {
                $stationaryData = $this->eloqM::find($inputDatas['front_id']);
                $stationaryData->sort = $inputDatas['front_sort'];
                $this->eloqM::where('sort', '>=', $inputDatas['front_sort'])->where('sort', '<', $inputDatas['rearways_sort'])->increment('sort');
                //下拉排序
            } elseif ($inputDatas['sort_type'] == 2) {
                $stationaryData = $this->eloqM::find($inputDatas['rearways_id']);
                $stationaryData->sort = $inputDatas['rearways_sort'];
                $this->eloqM::where('sort', '>', $inputDatas['front_sort'])->where('sort', '<=', $inputDatas['rearways_sort'])->decrement('sort');
            }
            $stationaryData->save();
            DB::commit();
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //公告置顶
    public function top(NoticeTopRequest $request)
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        if (is_null($pastEloq)) {
            return $this->msgOut(false, [], '102101');
        }
        $pastSort = $pastEloq->sort;
        DB::beginTransaction();
        try {
            $pastEloq->sort = 1;
            $pastEloq->save();
            $this->eloqM::where('sort', '<', $pastSort)->where('id', '!=', $inputDatas['id'])->increment('sort');
            DB::commit();
            //删除前台首页缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除前台首页缓存
    public function deleteCache()
    {
        if (Cache::has('homepageNotice')) {
            Cache::forget('homepageNotice');
        }
    }
}
