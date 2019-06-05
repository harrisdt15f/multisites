<?php

namespace App\Http\Controllers\BackendApi\Admin\Log;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\FrontendApiLog;

class HandleLogController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\PartnerLogsApi';

    //后台日志列表
    public function details()
    {
        $searchAbleFields = ['origin', 'ip', 'device', 'os', 'os_version', 'browser', 'admin_name', 'menu_label', 'device_type'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $data);
    }

    //前台日志列表
    public function frontendLogs()
    {
        $logEloq = new FrontendApiLog();
        $searchAbleFields = ['origin', 'ip', 'device', 'os', 'os_version', 'browser', 'username', 'menu_label', 'device_type'];
        $data = $this->generateSearchQuery($logEloq, $searchAbleFields);
        return $this->msgOut(true, $data);
    }
}
