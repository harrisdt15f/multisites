<?php

namespace App\Jobs;

use App\Lib\Pay\Panda;
use App\Models\User\UsersWithdrawHistorie;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WithdrawQuery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function handle()
    {
        try {
            $pandaC = new  Panda() ;
            $result =  $pandaC->queryWithdrawOrderStatus($this->data->order_id);
            if (array_get($result, 0) === true) {
                $datas['id']        = $this->data->id ;
                $datas['status']    = UsersWithdrawHistorie::WITHDRAWSUCCESS ;
                return UsersWithdrawHistorie::setWithdrawOrder($datas);
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::channel('pay-withdraw')->info('异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
        }
    }
}
