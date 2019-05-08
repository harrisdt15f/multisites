<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiMainController
{
    protected $eloqM = 'Category';
    //分类管理列表
    public function detail()
    {
        $datas = $this->eloqM::from('partner_category as self')->leftJoin('partner_category as secondary', 'self.parent', '=', 'secondary.id')->select('self.*', 'secondary.title as parent_title')->get()->toArray();
        if (empty($datas)) {
            return $this->msgOut(false, [], '没有获取到数据', '0009');
        }
        return $this->msgOut(true, $datas);
    }
    //操作文章时获取的分类列表
    public function select()
    {
        $datas = $this->eloqM::select('id', 'title')->get()->toArray();
        if (empty($datas)) {
            return $this->msgOut(false, [], '没有获取到数据', '0009');
        }
        return $this->msgOut(true, $datas);
    }
}
