<?php

namespace App\Jobs;

use App\Lib\Pay\Panda;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersWithdrawHistorie;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            if (array_get($result, '0') === true) {
                $datas['id']        = $this->data->id ;
                $datas['status']    = UsersWithdrawHistorie::WITHDRAWSUCCESS ;
                DB::beginTransaction();
                UsersWithdrawHistorie::setWithdrawOrder($datas);

                $userInfo = UsersWithdrawHistorie::where('order_id', '=', $this->data->order_id)->first();
                try {
                    $params = [
                    'user_id' => $userInfo->user_id,
                    'amount' =>  $userInfo->amount,
                    ];
                    $account  = FrontendUsersAccount::where('user_id', $userInfo->user_id)->first();

                    $account->operateAccount($params, 'withdraw_un_frozen');
                    $res = $account->operateAccount($params, 'withdraw_finish');
                    if ($res !== true) {
                        DB::rollBack();
                    }
                    DB::commit();
                    Log::channel('pay-withdraw')->info('提现成功:order_id '.$this->data->order_id);
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::channel('pay-withdraw')->info('异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::channel('pay-withdraw')->info('异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
        }
    }
}
