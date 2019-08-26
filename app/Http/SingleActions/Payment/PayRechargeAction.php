<?php
namespace App\Http\SingleActions\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\Pay\Panda;
use App\Models\User\Fund\FrontendUsersAccountsReport;
use App\Models\User\UserProfits;
use Illuminate\Http\JsonResponse;
use App\Models\User\UsersRechargeHistorie;
use Illuminate\Support\Facades\Log;

class PayRechargeAction
{
    /**
     * 获取可用充值网关
     * @param FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function getRechargeChannel(FrontendApiMainController $contll): JsonResponse
    {
        $pandaC = new  Panda() ;
        $result =  $pandaC->getRechargeChannel('web', $contll->currentAuth->user());
        return $contll->msgOut(true, $result);
    }

    /**
     * 发起充值
     * @param FrontendApiMainController $contll
     * @param $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function dorRecharge(FrontendApiMainController $contll, $request) : JsonResponse
    {
        $amount = $request->input('amount') ?? 0;
        $channel = $request->input('channel') ?? '';
        $from = $request->input('from') ?? 'web';

        $order = UsersRechargeHistorie::createRechargeOrder($contll->currentAuth->user(), $amount, $channel, $from);

        $pandaC = new  Panda() ;
        $result =  $pandaC->recharge($amount, $order->company_order_num, $channel, $from);

        if (array_get($result, 'status') == 'success') {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }

    /**
     * 处理回调
     * @param Controller $contll
     * @param $request
     * @return void
     */
    public function rechageCallback(Controller $contll, $request)
    {
        Log::channel('pay-recharge')->info('callBackInfo:'.json_encode($request->all()));
        $data = $request->all() ;

        $pandaC = new  Panda() ;
        if ($pandaC->checkRechargeCallbackSign($data) == true) {
            $pandaC::setRechargeOrderStatus($data, array_get($data, 'status'));
        } else {
            Log::channel('pay-recharge')->error('验签失败:'.json_encode($request->all()));
        }

        $pandaC->renderSuccess();
    }


    /**
     * 用户充值申请列表
     * @param FrontendApiMainController $contll
     * @param $request
     * @return JsonResponse
     */
    public function rechargeList(FrontendApiMainController $contll, $request) : JsonResponse
    {
        $dateTo = $request->input('date_to') ?? '';
        $dateFrom = $request->input('date_from') ?? '';
        $count = $request->input('count') ?? 15;
        $userInfo = $contll->currentAuth->user() ;

        if ($dateFrom && $dateTo) {
            $where = [['user_id', $userInfo->id], ['created_at', '>=', $dateFrom], ['created_at', '<=', $dateTo]];
        } else {
            $where = [['user_id', $userInfo->id]];
        }

        $rows = UsersRechargeHistorie::where($where)->paginate($count);

        return $contll->msgOut(true, $rows);
    }

    /**
     * 充值到账列表
     * @param FrontendApiMainController $contll
     * @param $request
     * @return JsonResponse
     */
    public function realRechargeList(FrontendApiMainController $contll, $request) : JsonResponse
    {
        $dateTo = $request->input('date_to') ?? '';
        $dateFrom = $request->input('date_from') ?? '';
        $count = $request->input('count') ?? 15;
        $userInfo = $contll->currentAuth->user() ;

        if ($dateFrom && $dateTo) {
            $where = [['user_id', $userInfo->id], ['created_at', '>=', $dateFrom], ['created_at', '<=', $dateTo]];
        } else {
            $where = [['user_id', $userInfo->id]];
        }

        $rows = FrontendUsersAccountsReport::where($where)->whereIn('type_sign', UserProfits::TEAM_DEPOSIT_SIGN)
            ->paginate($count);

        return $contll->msgOut(true, $rows);
    }
}
