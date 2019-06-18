<?php

namespace App\Http\Controllers\BackendApi\Admin\Article;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Article\ArticlesAddRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesDeleteRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesEditRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesSortRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesTopRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesUploadPicRequest;
use App\Lib\Common\ImageArrange;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Message\BackendSystemNoticeList;
use App\Models\BackendAdminAuditFlowList;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ArticlesController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Activity\BackendAdminMessageArticle';
    protected $message = '有新的文章需要审核';
    protected $folderName = 'articles';

    //文章列表
    public function detail(): JsonResponse
    {
        $field = 'sort';
        $type = 'asc';
        $searchAbleFields = ['title', 'type', 'search_text', 'is_for_agent'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields, 0, null, null, $field, $type);
        return $this->msgOut(true, $datas);
    }

    /**
     * 发布文章
     * @param   ArticlesAddRequest $request
     * @return  JsonResponse
     */
    public function add(ArticlesAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            //插入 backend_admin_audit_flow_lists 审核表
            $auditFlowId = $this->insertAuditFlow($inputDatas['apply_note']);
            //插入 BackendAdminMessageArticle 文章表
            $addDatas = $inputDatas;
            $addDatas['audit_flow_id'] = $auditFlowId;
            unset($addDatas['pic_name']);
            $maxSort = $this->eloqM::select('sort')->max('sort');
            $sort = ++$maxSort;
            $addDatas['sort'] = $sort;
            $addDatas['status'] = 0;
            $addDatas['add_admin_id'] = $this->partnerAdmin['id'];
            $addDatas['last_update_admin_id'] = $this->partnerAdmin['id'];
            if (isset($inputDatas['pic_path']) && $inputDatas['pic_path'] !== '') {
                $addDatas['pic_path'] = implode('|', $inputDatas['pic_path']);
            }
            $articlesEloq = new $this->eloqM();
            $articlesEloq->fill($addDatas);
            $articlesEloq->save();
            //文章发布成功  销毁图片缓存
            if (isset($inputDatas['pic_path'])) {
                $this->deleteCachePic($inputDatas['pic_name']);
            }
            //发送站内消息给管理员审核
            $this->sendMessage();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 编辑文章
     * @param  ArticlesEditRequest $request
     * @return JsonResponse
     */
    public function edit(ArticlesEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $issetTitle = $this->eloqM::where('title', $inputDatas['title'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($issetTitle === true) {
            return $this->msgOut(false, [], '100500');
        }
        try {
            $pastEloq = $this->eloqM::find($inputDatas['id']);
            //插入 backend_admin_audit_flow_lists 审核表
            $auditFlowId = $this->insertAuditFlow($inputDatas['apply_note']);
            $pastEloq->audit_flow_id = $auditFlowId;
            //
            $pastPicPath = $pastEloq->pic_path;
            $editDatas = $inputDatas;
            unset($editDatas['pic_name'], $editDatas['apply_note']);
            $this->editAssignment($pastEloq, $editDatas);
            $pastEloq->status = 0;
            $pastEloq->last_update_admin_id = $this->partnerAdmin['id'];
            //查看是否修改图片
            $new_pic_path = $inputDatas['pic_path'];
            if ($new_pic_path != $pastPicPath) {
                //销毁缓存
                $this->deleteCachePic($inputDatas['pic_name']);
                //删除原图
                $pastPicPathArr = explode('|', $pastPicPath);
                $this->deleteImg($pastPicPathArr);
                //
                $pastEloq->pic_path = implode('|', $new_pic_path);
            }
            $pastEloq->save();
            //发送站内消息给管理员审核
            $this->sendMessage();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除文章
     * @param  ArticlesDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(ArticlesDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        $picPathArr = explode('|', $pastDataEloq->pic_path);
        DB::beginTransaction();
        try {
            $this->eloqM::find($inputDatas['id'])->delete();
            //排序
            $this->eloqM::where('sort', '>', $pastDataEloq->sort)->decrement('sort');
            //删除图片
            $this->deleteImg($picPathArr);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 文章排序
     * @param  ArticlesSortRequest $request
     * @return JsonResponse
     */
    public function sort(ArticlesSortRequest $request): JsonResponse
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
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 文章置顶
     * @param  ArticlesTopRequest $request
     * @return JsonResponse
     */
    public function top(ArticlesTopRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $topData = $this->eloqM::find($inputDatas['id']);
        try {
            $this->eloqM::where('sort', '<', $topData['sort'])->increment('sort');
            $topData->sort = 1;
            $topData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 图片上传
     * @param  ArticlesUploadPicRequest $request
     * @return JsonResponse
     */
    public function uploadPic(ArticlesUploadPicRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        //接收文件信息
        $imageObj = new ImageArrange();
        $file = $inputDatas['pic'];
        $depositPath = $imageObj->depositPath($this->folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        //进行上传
        $pic = $imageObj->uploadImg($file, $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        $hourToStore = 24 * 2;
        $expiresAt = Carbon::now()->addHours($hourToStore);
        if (Cache::has('CachePic')) {
            $cachePic = Cache::get('CachePic');
            $cachePic[$pic['name']] = $pic;
        } else {
            $cachePic[$pic['name']] = $pic;
        }
        Cache::put('CachePic', $cachePic, $expiresAt);
        return $this->msgOut(true, $pic);
    }

    /**
     * 删除图片缓存
     * @param  array $picNames 图片名称
     * @return void
     */
    public function deleteCachePic(array $picNames)
    {
        if (Cache::has('CachePic')) {
            $cachePic = Cache::get('CachePic');
            foreach ($picNames as $picName) {
                if (array_key_exists($picName, $cachePic)) {
                    unset($cachePic[$picName]);
                }
            }
            $hourToStore = 24 * 2;
            $expiresAt = Carbon::now()->addHours($hourToStore);
            Cache::put('CachePic', $cachePic, $expiresAt);
        }
    }

    /**
     * 插入审核表
     * @param  string $apply_note 备注
     * @return int
     */
    public function insertAuditFlow($apply_note)
    {
        $flowDatas = [
            'admin_id' => $this->partnerAdmin['id'],
            'apply_note' => $apply_note,
            'admin_name' => $this->partnerAdmin['name'],
        ];
        $flowConfigure = new BackendAdminAuditFlowList;
        $flowConfigure->fill($flowDatas);
        $flowConfigure->save();
        return $flowConfigure->id;
    }

    /**
     * 删除图片
     * @param  array $imgArr
     * @return void
     */
    public function deleteImg(array $imgArr)
    {
        $imageObj = new ImageArrange();
        foreach ($imgArr as $imgPath) {
            $imageObj->deletePic($imgPath);
        }
    }

    /**
     * 发送站内消息给管理员审核
     * @return void
     */
    public function sendMessage()
    {
        $messageClass = new InternalNoticeMessage();
        $type = BackendSystemNoticeList::AUDIT;
        $message = $this->message;
        $adminsArr = BackendAdminUser::select('id', 'group_id')->where('group_id', 1)->get()->toArray();
        $messageClass->insertMessage($type, $message, $adminsArr);
    }
}
