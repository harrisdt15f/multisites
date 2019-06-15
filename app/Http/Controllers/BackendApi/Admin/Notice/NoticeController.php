<?php

namespace App\Http\Controllers\BackendApi\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Notice\NoticeAddRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeDeleteRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeEditRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeSortRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeTopRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NoticeController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Notice\FrontendMessageNotice';

    /**
     * 公告列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $noticeDatas = $this->eloqM::select('id', 'type', 'title', 'content', 'start_time', 'end_time', 'sort', 'status', 'admin_id')->with('admin')->orderBy('sort', 'asc')->get()->toArray();
        foreach ($noticeDatas as $key => $data) {
            $noticeDatas[$key]['admin_name'] = $data['admin']['name'];
            unset($noticeDatas[$key]['admin_id'], $noticeDatas[$key]['admin']);
        }
        return $this->msgOut(true, $noticeDatas);
    }

    /**
     * 添加公告
     * @param  NoticeAddRequest $request
     * @return JsonResponse
     */
    public function add(NoticeAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $addData = $inputDatas;
        $addData['admin_id'] = $this->partnerAdmin->id;
        $maxSort = $this->eloqM::select('sort')->max('sort');
        $addData['sort'] = ++$maxSort;
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

    /**
     * 编辑公告
     * @param  NoticeEditRequest $request
     * @return JsonResponse
     */
    public function edit(NoticeEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        $checkTitle = $this->eloqM::where('title', $inputDatas['title'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($checkTitle === true) {
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

    /**
     * 删除公告
     * @param  NoticeDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(NoticeDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        //sort
        $sort = $pastDataEloq->sort;
        DB::beginTransaction();
        try {
            $pastDataEloq->delete();
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

    /**
     * 公告排序
     * @param  NoticeSortRequest $request
     * @return JsonResponse
     */
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
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 公告置顶
     * @param  NoticeTopRequest $request
     * @return JsonResponse
     */
    public function top(NoticeTopRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        $sort = $pastEloq->sort;
        DB::beginTransaction();
        try {
            $pastDataEloq->sort = 1;
            $pastDataEloq->save();
            $this->eloqM::where('sort', '<', $sort)->where('id', '!=', $inputDatas['id'])->increment('sort');
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

    /**
     * 删除前台首页缓存
     * @return void
     */
    public function deleteCache(): void
    {
        if (Cache::has('homepageNotice')) {
            Cache::forget('homepageNotice');
        }
    }
}
