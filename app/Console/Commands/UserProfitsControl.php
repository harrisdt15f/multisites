<?php
/**
 * 团队盈亏处理脚本
 * 每5分钟运行一次，更新盈亏数据到user_prifits
 */

namespace App\Console\Commands;

use App\Models\User\Fund\FrontendUsersAccountsReport;
use App\Models\User\UserProfits;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
//use Illuminate\Support\Facades\Log;

class UserProfitsControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UserProfits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '团队盈亏处理脚本';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today =  Carbon::now()->toDateString();

        $todayAccountsReportsUsers = FrontendUsersAccountsReport::where([
            ['created_at', '>', $today],
        ])
            ->select('username','user_id','is_tester','parent_id')
            ->groupby('username')
            ->get();


        if (is_object($todayAccountsReportsUsers)){
            foreach ($todayAccountsReportsUsers as $child){

                $data['team_deposit'] = UserProfits::getSumProfits($today, $child->username, UserProfits::TEAM_DEPOSIT_SIGN) ;
                $data['team_withdrawal'] = UserProfits::getSumProfits($today, $child->username, UserProfits::TEAM_WITHDRAWAL_SIGN) ;
                $data['team_turnover'] = UserProfits::getSumProfits($today, $child->username, UserProfits::TEAM_TURNOVER_SIGN) ;
                $data['team_prize'] = UserProfits::getSumProfits($today, $child->username, UserProfits::TEAM_PRIZE_SIGN) ;
                $data['team_profit'] = $data['team_prize'] -  $data['team_turnover'];
                $data['team_commission'] = 0 ;  //todo 下级返点
                $data['team_bet_commission'] = 0 ;  //todo 投注返点
                $data['team_dividend'] = UserProfits::getSumProfits($today, $child->username, UserProfits::TEAM_DVIVDEND_SIGN) ;
                $data['team_daily_salary'] = UserProfits::getSumProfits($today, $child->username, UserProfits::TEAM_DAILYSALARY_SIGN) ;

                $data['date'] = $today;
                $data['user_id'] =  $child->user_id;
                $data['username'] =  $child->username;
                $data['is_tester'] =  $child->is_tester;
                $data['parent_id'] =  $child->parent_id;

                UserProfits::updateProfits($data);
            }
        }
    }
}
