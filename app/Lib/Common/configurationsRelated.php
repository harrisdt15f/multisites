<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-27 15:08:33
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-27 15:22:43
 */
namespace App\Lib\Common;

use App\Models\Admin\SystemConfiguration;

class configurationsRelated
{
    public function create($parentId = 0, $sign, $name, $description, $value, $status, $display, $addAdminId = 0, $lastUpdateAdminId = 0, $pid = 1)
    {
        $configELoq = new SystemConfiguration();
        $addData = [
            'parent_id' => $parentId,
            'pid' => $pid,
            'sign' => $sign,
            'name' => $name,
            'description' => $description,
            'value' => $value,
            'add_admin_id' => $addAdminId,
            'last_update_admin_id' => $lastUpdateAdminId,
            'status' => $status,
            'display' => $display,
        ];
        $configELoq->fill($addData);
        $configELoq->save();
        return $configELoq;
    }
}
