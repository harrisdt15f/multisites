<?php

namespace App\Http\Controllers\BackendApi\Admin\Log;


use App\Http\Controllers\BackendApi\BackEndApiMainController;

class HandleLogController extends BackEndApiMainController
{
    protected $eloqM = 'PartnerLogsApi';

    public function details()
    {
        $searchAbleFields = ['origin', 'ip', 'device', 'os','os_version','browser','admin_name','menu_label','device_type'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $data);
    }
}
