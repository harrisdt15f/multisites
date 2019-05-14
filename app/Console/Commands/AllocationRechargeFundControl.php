<?php

namespace App\Console\Commands;

use App\models\ArtificialRechargeLog;
use App\models\FundOperation;
use App\models\FundOperationGroup;
use App\models\PartnerAdminUsers;
use App\models\PartnerSysConfigures;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AllocationRechargeFundControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AllocationRechargeFund';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时发放人工充值额度';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('开始定时发放人工充值额度');
        $FundOperation = new FundOperation();
        $ArtificialRechargeLog = new ArtificialRechargeLog();
        $AdminUsersEloq = new PartnerAdminUsers();
        $PartnerSysConfigures = new PartnerSysConfigures();
        $FundOperationGroup = new FundOperationGroup();
        $fundData = $PartnerSysConfigures->select('value')->where('sign', 'admin_recharge_daily_limit')->first();
        $everyDayfund = $fundData['value'];
        $groupArr = $FundOperationGroup->get()->toArray();
        $groups = array_column($groupArr, 'group_id');
        //拥有权限的管理员
        $admins = PartnerAdminUsers::from('partner_admin_users as admin')
            ->select('admin.*', 'fund.fund')
            ->leftJoin('fund_operation as fund', 'fund.admin_id', '=', 'admin.id')
            ->whereIn('group_id', $groups)
            ->get()->toArray();
        $time = date('Y-m-d H:i:s', time());
        foreach ($admins as $k => $v) {
            if ($v['fund'] < $everyDayfund) {
                $fund = $everyDayfund;
                $addFund = $fund - $v['fund'];
                $adminFund = $v['fund'] + $addFund;
                $type = ArtificialRechargeLog::SYSTEM;
                $in_out = ArtificialRechargeLog::INCREMENT;
                $comment = '[每日充值额度发放]=>>+' . $addFund . '|[目前额度]=>>' . $adminFund;
                $editFundData = [
                    'fund' => $fund,
                    'updated_at' => $time,
                ];
                $flowsData = [
                    'admin_id' => $v['id'],
                    'admin_name' => $v['name'],
                    'comment' => $comment,
                    'type' => $type,
                    'in_out' => $in_out,
                    'created_at' => $time,
                    'updated_at' => $time,
                ];
                DB::beginTransaction();
                try {
                    $FundOperation->where('admin_id', $v['id'])->update($editFundData);
                    $ArtificialRechargeLog->insert($flowsData);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }
    }
}
