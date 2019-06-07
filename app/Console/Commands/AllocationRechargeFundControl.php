<?php

namespace App\Console\Commands;

use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\SystemConfiguration;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
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
        $sysConfigures = new SystemConfiguration();
        $fundData = $sysConfigures->select('value')->where('sign', 'admin_recharge_daily_limit')->first();
        $everyDayfund = $fundData['value'];
        $fundOperationGroup = new BackendAdminRechargePermitGroup();
        $groupArr = $fundOperationGroup->get()->toArray();
        $groups = array_column($groupArr, 'group_id');
        //拥有权限的管理员
        $admins = BackendAdminUser::from('backend_admin_users as admin')
            ->select('admin.*', 'fund.fund')
            ->leftJoin('backend_admin_recharge_pocess_amounts as fund', 'fund.admin_id', '=', 'admin.id')
            ->whereIn('group_id', $groups)
            ->get()->toArray();
        $time = date('Y-m-d H:i:s', time());
        foreach ($admins as $admin) {
            if ($admin['fund'] < $everyDayfund) {
                $fund = $everyDayfund;
                $addFund = $fund - $admin['fund'];
                $adminFund = $admin['fund'] + $addFund;
                $type = BackendAdminRechargehumanLog::SYSTEM;
                $in_out = BackendAdminRechargehumanLog::INCREMENT;
                $comment = '[每日充值额度发放]=>>+' . $addFund . '|[目前额度]=>>' . $adminFund;
                $editFundData = [
                    'fund' => $fund,
                    'updated_at' => $time,
                ];
                $flowsData = [
                    'admin_id' => $admin['id'],
                    'admin_name' => $admin['name'],
                    'comment' => $comment,
                    'type' => $type,
                    'in_out' => $in_out,
                    'created_at' => $time,
                    'updated_at' => $time,
                ];
                DB::beginTransaction();
                try {
                    $fundOperation = new BackendAdminRechargePocessAmount();
                    $fundOperation->where('admin_id', $admin['id'])->update($editFundData);
                    $rechargeLog = new BackendAdminRechargehumanLog();
                    $rechargeLog->insert($flowsData);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }
    }
}
