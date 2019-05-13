<?php

namespace App\Http\Controllers\BackendApi;

use App\Http\Controllers\ApiMainController;

class HandleLogController extends ApiMainController
{
    protected $eloqM = 'PartnerLogsApi';

    public function details()
    {
        $searchAbleFields = ['origin', 'ip', 'device', 'os','os_version','browser','admin_name','menu_label','device_type'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $data);
    }
}
