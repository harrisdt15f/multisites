<?php

namespace App\Http\Controllers\BackendApi\Admin\Log;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\IpAddress;
use App\Models\Admin\FrontendSystemLog;
use App\Models\Admin\SystemAddressIp;
use Illuminate\Support\Facades\Validator;

class HandleLogController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\BackendSystemLog';
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
        $logEloq = new FrontendSystemLog();
        $searchAbleFields = ['origin', 'ip', 'device', 'os', 'os_version', 'browser', 'username', 'menu_label', 'device_type'];
        $data = $this->generateSearchQuery($logEloq, $searchAbleFields);
        return $this->msgOut(true, $data);
    }

    //IP获取地址
    public function getAddress()
    {
        $validator = Validator::make($this->inputs, [
            'ip' => 'required|ip',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $addressIpELoq = SystemAddressIp::where('ip', $this->inputs['ip'])->first();
        if (is_null($addressIpELoq)) {
            $ipAddressCla = new IpAddress();
            $addressIpELoq = $ipAddressCla->getAddress($this->inputs['ip']);
        }
        $data = [
            'ip' => $addressIpELoq->ip,
            'country' => $addressIpELoq->country,
            'region' => $addressIpELoq->region,
            'city' => $addressIpELoq->city,
            'county' => $addressIpELoq->county,
        ];
        return $this->msgOut(true, $data);
    }
}
