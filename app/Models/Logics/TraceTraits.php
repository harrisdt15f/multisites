<?php

namespace App\Models\Logics;

use App\Models\Trace;

/**
 * @Author: LingPh
 * @Date:   2019-05-29 17:49:50
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-29 17:50:27
 */
trait TraceTraits
{
    // 获取列表
    public static function getList($condition)
    {
        $query = Trace::orderBy('id', 'desc');
        if (isset($condition['en_name'])) {
            $query->where('en_name', '=', $condition['en_name']);
        }
        $currentPage = isset($condition['page_index']) ? intval($condition['page_index']) : 1;
        $pageSize = isset($condition['page_size']) ? intval($condition['page_size']) : 15;
        $offset = ($currentPage - 1) * $pageSize;
        $total = $query->count();
        $menus = $query->skip($offset)->take($pageSize)->get();
        return ['data' => $menus, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }
}
