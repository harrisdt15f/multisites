<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends ApiMainController
{
    protected $eloqM = 'Articles';
    //文章列表
    public function detail()
    {
        $searchAbleFields = ['title', 'type', 'search_text', 'is_for_agent'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
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
            'status' => 'required|in:0,1,2,3',
            'is_for_agent' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
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
        $addDatas = [
            'category_id' => $this->inputs['category_id'],
            'title' => $this->inputs['title'],
            'summary' => $this->inputs['summary'],
            'content' => $this->inputs['content'],
            'search_text' => $this->inputs['search_text'],
            'is_for_agent' => $this->inputs['is_for_agent'],
            'status' => $this->inputs['status'],
            'add_admin_id' => $this->partnerAdmin['id'],
            'last_update_admin_id' => $this->partnerAdmin['id'],
            'sort' => $sort,
        ];
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgout(true, [], '发布文章成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
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
            'status' => 'required|in:0,1,2,3',
            'is_for_agent' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($pastData)) {
            return $this->msgout(false, [], '该文章名已存在', '0009');
        }
        $editDataEloq = $this->eloqM::find($this->inputs['id']);
        $editDataEloq->category_id = $this->inputs['category_id'];
        $editDataEloq->title = $this->inputs['title'];
        $editDataEloq->summary = $this->inputs['summary'];
        $editDataEloq->content = $this->inputs['content'];
        $editDataEloq->search_text = $this->inputs['search_text'];
        $editDataEloq->status = $this->inputs['status'];
        $editDataEloq->is_for_agent = $this->inputs['is_for_agent'];
        $editDataEloq->last_update_admin_id = $this->partnerAdmin['id'];
        try {
            $editDataEloq->save();
            return $this->msgout(true, [], '修改文章成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
    //删除文章
    public function deleteArticles()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (!is_null($pastData)) {
            try {
                $this->eloqM::where('id', $this->inputs['id'])->delete();
                return $this->msgout(true, [], '删除文章成功');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '该文章不存在', '0009');
        }
    }
    //文章排序
    public function sortArticles()
    {
        $validator = Validator::make($this->inputs, [
            'front_sort' => 'required|numeric',
            'rearways_sort' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        if ($this->inputs['front_sort'] > $this->inputs['rearways_sort']) {
            return $this->msgout(false, [], 'front_sort必须小于rearways_sort');
        }
        $frontData = $this->eloqM::where('sort', $this->inputs['front_sort'])->first()->toArray();
        $rearways_sort = $this->eloqM::where('sort', $this->inputs['rearways_sort'])->first();
        if (is_null($frontData) || is_null($rearways_sort)) {
            return $this->msgout(false, [], '需要排序的sort不存在');
        }
        $rearways_sort->sort = $frontData['sort'];
        try {
            $this->eloqM::where('sort', '>=', $this->inputs['front_sort'])->where('sort', '<', $this->inputs['rearways_sort'])->increment('sort');
            $rearways_sort->save();
            return $this->msgout(true, [], '文章排序成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
    //文章置顶
    public function topArticles()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'sort' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $topData = $this->eloqM::find($this->inputs['id']);
        if (is_null($topData)) {
            return $this->msgout(false, [], '需要置顶的文章不存在');
        }
        if ($topData->sort != $this->inputs['sort']) {
            return $this->msgout(false, [], '需要置顶的文章ID与sort不匹配');
        }
        try {
            $this->eloqM::where('sort', '<', $this->inputs['sort'])->increment('sort');
            $topData->sort = 1;
            $topData->save();
            return $this->msgout(true, [], '文章置顶成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
}
