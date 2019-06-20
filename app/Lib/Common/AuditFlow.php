<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-20 16:44:35
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-20 16:46:42
 */
namespace App\Lib\Common;

use App\Models\BackendAdminAuditFlowList;
use Illuminate\Support\Facades\Cache;

class AuditFlow
{
    /**
     * 插入审核表
     * @param   string $apply_note [备注]
     * @return  int
     */
    public function insertAuditFlow($apply_note): int
    {
        if (!Cache::has('partnerAdmin')) {
            return $contll->msgOut(false, [], '100302');
        }
        $partnerAdmin = Cache::get('partnerAdmin');
        $flowDatas = [
            'admin_id' => $partnerAdmin->id,
            'apply_note' => $apply_note,
            'admin_name' => $partnerAdmin->name,
        ];
        $flowConfigure = new BackendAdminAuditFlowList;
        $flowConfigure->fill($flowDatas);
        $flowConfigure->save();
        return $flowConfigure->id;
    }
}
