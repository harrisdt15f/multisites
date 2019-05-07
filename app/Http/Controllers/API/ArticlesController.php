<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\AuditFlow;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends ApiMainController
{
    protected $eloqM = 'Articles';
    //文章列表
    public function detail()
    {
        $field = 'sort';
        $type = 'asc';
        $searchAbleFields = ['title', 'type', 'search_text', 'is_for_agent'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields, 0, null, null, $field, $type);
        if (empty($datas)) {
            return $this->msgout(false, [], '没有获取到数据', '0009');
        }
        return $this->msgout(true, $datas);
    }
    //发布文章
    public function addArticles()
    {
        $validator = Validator::make($this->inputs, [
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'search_text' => 'required|string',
            'is_for_agent' => 'required|in:0,1',
            'apply_note' => 'required|string',
            'pic_name' => 'array',
            'pic_path' => 'array',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($pastData)) {
            return $this->msgout(false, [], '该文章名已存在', '0009');
        }
        $sortdata = $this->eloqM::orderBy('sort', 'desc')->first();
        if (is_null($sortdata)) {
            $sort = 1;
        } else {
            $sort = $sortdata['sort'] + 1;
        }
        $addDatas = $this->inputs;
        unset($addDatas['pic_name']);
        $addDatas['status'] = 0;
        $addDatas['add_admin_id'] = $this->partnerAdmin['id'];
        $addDatas['last_update_admin_id'] = $this->partnerAdmin['id'];
        $addDatas['sort'] = $sort;
        if (array_key_exists('pic_path', $this->inputs) && !empty($this->inputs['pic_path'])) {
            $addDatas['pic_path'] = implode('|', $this->inputs['pic_path']);
        }
        $flowDatas = [
            'admin_id' => $this->partnerAdmin['id'],
            'apply_note' => $this->inputs['apply_note'],
            'admin_name' => $this->partnerAdmin['name'],
        ];
        try {
            $flowConfigure = new AuditFlow;
            $flowConfigure->fill($flowDatas);
            $flowConfigure->save();
            $configure = new $this->eloqM();
            $addDatas['audit_flow_id'] = $flowConfigure->id;
            $configure->fill($addDatas);
            $configure->save();
            //文章发布成功  销毁缓存
            if (array_key_exists('pic_path', $this->inputs)) {
                if (Cache::has('CachePic')) {
                    $CachePic = Cache::get('CachePic');
                    foreach ($this->inputs['pic_name'] as $k => $v) {
                        if (array_key_exists($v, $CachePic)) {
                            unset($CachePic[$v]);
                        }
                    }
                    $minutes = 2 * 24 * 60;
                    Cache::put('CachePic', $CachePic, $minutes);
                }
            }
            return $this->msgout(true, [], '发布文章成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $sqlState, $msg);
        }
    }
    //编辑文章
    public function editArticles()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'search_text' => 'required|string',
            'is_for_agent' => 'required|in:0,1',
            'apply_note' => 'required|string',
            'pic_name' => 'required|array',
            'pic_path' => 'required|array',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($pastData)) {
            return $this->msgout(false, [], '该文章名已存在', '0009');
        }
        $editDataEloq = $this->eloqM::find($this->inputs['id']);
        $past_pic_path = $editDataEloq->pic_path;
        $editDatas = $this->inputs;
        unset($editDatas['pic_name']);
        unset($editDatas['pic_name']);
        unset($editDatas['apply_note']);
        $this->editAssignment($editDataEloq, $editDatas);
        $editDataEloq->status = 0;
        $editDataEloq->last_update_admin_id = $this->partnerAdmin['id'];
        $flowDatas = [
            'admin_id' => $this->partnerAdmin['id'],
            'apply_note' => $this->inputs['apply_note'],
            'admin_name' => $this->partnerAdmin['name'],
        ];
        try {
            //获取图片路径
            $new_pic_path = $this->inputs['pic_path'];
            if ($new_pic_path != $past_pic_path) {
                //销毁缓存
                $CachePic = Cache::get('CachePic');
                foreach ($this->inputs['pic_name'] as $k => $v) {
                    if (array_key_exists($v, $CachePic)) {
                        unset($CachePic[$v]);
                    }
                }
                $minutes = 2 * 24 * 60;
                Cache::put('CachePic', $CachePic, $minutes);
                //删除原图
                $past_pic_path_arr = explode('|', $past_pic_path);
                foreach ($past_pic_path_arr as $k => $v) {
                    $this->deleteArticlePic($v);
                }
                $editDataEloq->pic_path = implode('|', $new_pic_path);
            }
            $flowConfigure = new AuditFlow;
            $flowConfigure->fill($flowDatas);
            $flowConfigure->save();
            $editDataEloq->audit_flow_id = $flowConfigure->id;
            $editDataEloq->save();
            return $this->msgout(true, [], '修改文章成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $sqlState, $msg);
        }
    }
    //删除文章
    public function deleteArticles()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        $pic_path = explode('|', $pastData['pic_path']);
        if (!is_null($pastData)) {
            try {
                $this->eloqM::where('id', $this->inputs['id'])->delete();
                $this->eloqM::where('sort', '>', $pastData['sort'])->decrement('sort');
                foreach ($pic_path as $k => $v) {
                    $this->deleteArticlePic($v);
                }
                return $this->msgout(true, [], '删除文章成功');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgout(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgout(false, [], '该文章不存在', '0009');
        }
    }
    //文章排序
    public function sortArticles()
    {
        $validator = Validator::make($this->inputs, [
            'front_id' => 'required|numeric|gt:0',
            'rearways_id' => 'required|numeric|gt:0',
            'front_sort' => 'required|numeric|gt:0',
            'rearways_sort' => 'required|numeric|gt:0',
            'sort_type' => 'required|in:1,2',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        try {
            //上拉排序
            if ($this->inputs['sort_type'] == 1) {
                $frontData = $this->eloqM::find($this->inputs['front_id']);
                if (!isset($frontData)) {
                    return $this->msgout(false, [], '需要排序的文章不存在');
                }
                $frontData->sort = $this->inputs['front_sort'];
                $this->eloqM::where('sort', '>=', $this->inputs['front_sort'])->where('sort', '<', $this->inputs['rearways_sort'])->increment('sort');
                //下拉排序
            } elseif ($this->inputs['sort_type'] == 2) {
                $frontData = $this->eloqM::find($this->inputs['rearways_id']);
                if (!isset($frontData)) {
                    return $this->msgout(false, [], '需要排序的文章不存在');
                }
                $frontData->sort = $this->inputs['rearways_sort'];
                $this->eloqM::where('sort', '>', $this->inputs['front_sort'])->where('sort', '<=', $this->inputs['rearways_sort'])->decrement('sort');
            }
            $frontData->save();
            return $this->msgout(true, [], '文章排序成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $sqlState, $msg);
        }
    }
    //文章置顶
    public function topArticles()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        $topData = $this->eloqM::find($this->inputs['id']);
        if (is_null($topData)) {
            return $this->msgout(false, [], '需要置顶的文章不存在');
        }
        try {
            $this->eloqM::where('sort', '<', $topData['sort'])->increment('sort');
            $topData->sort = 1;
            $topData->save();
            return $this->msgout(true, [], '文章置顶成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $sqlState, $msg);
        }
    }
    //图片上传
    public function uploadPic()
    {
        $validator = Validator::make($this->inputs, [
            'pic' => 'required|file',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        //接收文件信息
        $file = $this->inputs['pic'];
        $path = 'uploaded_files/' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id . '/articles_' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id;
        $rule = ['jpg', 'png', 'gif'];
        //进行上传
        $pic = $this->uploadImg($file, $path, $rule);
        if ($pic['success'] === false) {
            return $this->msgout(false, [], $pic['message'], '0009');
        }
        $minutes = 2 * 24 * 60;
        $pic['expire_time'] = (time() + 60 * 30) . '';
        if (Cache::has('CachePic')) {
            $CachePic = Cache::get('CachePic');
            $CachePic[$pic['name']] = $pic;
        } else {
            $CachePic[$pic['name']] = $pic;
        }
        Cache::put('CachePic', $CachePic, $minutes);
        return $this->msgout(true, $pic, '图片上传成功');
    }
    public function uploadImg($file, $url_path, $rule)
    {
        // 检验一下上传的文件是否有效.
        if ($file->isValid()) {
            // 缓存在tmp文件夹中的文件名 例如 php8933.tmp 这种类型的.
            $clientName = $file->getClientOriginalName();
            // 上传文件的后缀.
            $entension = $file->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                return ['success' => false, 'message' => '图片格式为jpg,png,gif'];
            }
            $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
            if (!file_exists($url_path)) {
                mkdir($url_path, 0777, true);
            }
            if (!is_writable(dirname($url_path))) {
                return ['success' => false, 'message' => dirname($url_path) . ' 请设置权限!!!'];
            } else {
                $file->move($url_path, $newName);
            }
            // 这里public_path()就是public文件夹所在的路径.$newName 通过算法获得的文件的名称.主要是不能重复产生冲突即可.
            // 利用日期和客户端文件名结合 使用md5 算法加密得到结果.后面加上文件原始的拓展名.
            //文件名
            $namePath = $url_path . '/' . $newName;
            return ['success' => true, 'name' => $newName, 'path' => $namePath];
        }
    }
    public function deleteArticlePic($path)
    {
        if (file_exists($path)) {
            if (!is_writable(dirname($path))) {
                return $this->msgout(true, [], dirname($path) . ' 请设置权限!!!');
            } else {
                return unlink($path);
            }
        }
    }
}
