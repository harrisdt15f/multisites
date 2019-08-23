<?php
namespace App\Http\SingleActions\Payment;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\Pay\Panda;
use Illuminate\Http\JsonResponse;
use App\Models\User\UsersWithdrawHistorie;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Request;

class PayWithdrawAction
{

    /**
     * 发起提现
     * @param FrontendApiMainController $contll
     * @param $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function applyWithdraw(FrontendApiMainController $contll, $request) : JsonResponse
    {
        $data['amount'] = $request->input('amount') ?? 0;
        $data['bank_sign']  = $request->input('bank_sign') ?? '';
        $data['card_number'] = $request->input('card_number') ?? '';
        $data['card_username'] = $request->input('card_username') ?? '';
        $data['from'] = $request->input('from') ?? 'web';


        $result = UsersWithdrawHistorie::createWithdrawOrder($contll->currentAuth->user(), $data);

        if ($result) {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }


    /**
     * 提现详情
     * @param BackEndApiMainController $contll
     * @return JsonResponse
     */
    public function detail(BackEndApiMainController $contll) : JsonResponse
    {
        $id = Request::input('id') ?? '';
        $result = UsersWithdrawHistorie::find($id);
        if ($result) {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }


    /**
     * 后台审核通过 提现
     * @param BackEndApiMainController $contll
     * @return JsonResponse
     */
    public function auditSuccess(BackEndApiMainController $contll) : JsonResponse
    {
        $datas['id'] = Request::input('id') ?? '';
        $datas['process_time'] = time();
        $datas['admin_id'] = $contll->currentAuth->user()->id ;
        $datas['status'] = UsersWithdrawHistorie::AUDITSUCCESS ;

        $result = UsersWithdrawHistorie::setWithdrawOrder($datas);
        Log::channel('pay-withdraw')->info('withdraw:【后台审核通过】' . json_encode($datas, true));
        if ($result) {
            //发起提现请求到panda
            $pandaC = new  Panda() ;
            $withDrawInfo = UsersWithdrawHistorie::find($datas['id']) ;
            $result =  $pandaC->withdrawal($withDrawInfo);

            if ($result[0] == false) {
                $datas['status'] = UsersWithdrawHistorie::UNDERWAYAUDIT ;
                $withDrawInfo->update($datas);
            }
            return $contll->msgOut($result[0], '', '', $result[1]);
        } else {
            return $contll->msgOut(false, $result);
        }
    }

    /**
     * 后台审核不通过 提现
     * @param BackEndApiMainController $contll
     * @return JsonResponse
     */
    public function auditFailure(BackEndApiMainController $contll) : JsonResponse
    {
        $datas['id'] = Request::input('id') ?? '';
        $datas['process_time'] = time();
        $datas['admin_id'] = $contll->currentAuth->user()->id ;
        $datas['status'] = UsersWithdrawHistorie::AUDITFAILURE ;

        $result = UsersWithdrawHistorie::setWithdrawOrder($datas);
        if ($result) {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }
}
