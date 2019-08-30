<?php

namespace App\Models\Logics;

trait TraceTraits
{
    /**
     * 获取列表
     * @param  array $condition
     * @return array
     */
    public static function getList($condition): array
    {
        $query = self::orderBy('id', 'desc');
        if (isset($condition['en_name'])) {
            $query->where('en_name', '=', $condition['en_name']);
        }
        $currentPage = isset($condition['page_index']) ? (int) $condition['page_index'] : 1;
        $pageSize = isset($condition['page_size']) ? (int) $condition['page_size'] : 15;
        $offset = ($currentPage - 1) * $pageSize;
        $total = $query->count();
        $menus = $query->skip($offset)->take($pageSize)->get();
        return [
            'data' => $menus,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => (int) ceil($total / $pageSize),
        ];
    }
}
