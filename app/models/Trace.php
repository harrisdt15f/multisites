<?php

namespace App\models;

class Trace extends BaseModel
{
    protected $table = 'traces';

    // 获取列表
    static function getList($condition) {
        $query = self::orderBy('id', 'desc');
        if (isset($condition['en_name'])) {
            $query->where('en_name', '=', $condition['en_name']);
        }
        $currentPage    = isset($condition['page_index']) ? intval($condition['page_index']) : 1;
        $pageSize       = isset($condition['page_size']) ? intval($condition['page_size']) : 15;
        $offset         = ($currentPage - 1) * $pageSize;
        $total  = $query->count();
        $menus  = $query->skip($offset)->take($pageSize)->get();
        return ['data' => $menus, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }
}
