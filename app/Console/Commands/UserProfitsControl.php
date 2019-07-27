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

                $data['team_deposit'] = Self::getSumProfits($today, $child->username, UserProfits::TEAM_DEPOSIT_SIGN) ;
                $data['team_withdrawal'] = Self::getSumProfits($today, $child->username, UserProfits::TEAM_WITHDRAWAL_SIGN) ;
                $data['team_turnover'] = Self::getSumProfits($today, $child->username, UserProfits::TEAM_TURNOVER_SIGN) ;
                $data['team_prize'] = Self::getSumProfits($today, $child->username, UserProfits::TEAM_PRIZE_SIGN) ;
                $data['team_profit'] = $data['team_prize'] -  $data['team_turnover'];
                $data['team_commission'] = 0 ;  //todo 下级返点
                $data['team_bet_commission'] = 0 ;  //todo 投注返点
                $data['team_dividend'] = Self::getSumProfits($today, $child->username, UserProfits::TEAM_DVIVDEND_SIGN) ;
                $data['team_daily_salary'] = Self::getSumProfits($today, $child->username, UserProfits::TEAM_DAILYSALARY_SIGN) ;

                $data['date'] = $today;
                $data['user_id'] =  $child->user_id;
                $data['username'] =  $child->username;
                $data['is_tester'] =  $child->is_tester;
                $data['parent_id'] =  $child->parent_id;

                Self::updateProfits($data);
            }
        }
    }

    public static function getSumProfits(string $date, string $username, array $type_sign) : float
    {
        return FrontendUsersAccountsReport::where([
            ['created_at', '>', $date],
            ['username', $username]
        ])
            ->whereIn('type_sign', $type_sign)
            ->sum('amount');
    }


    public static function updateProfits(array $data) : bool
    {
        if($data['user_id'] && $data['date']){
            $row = UserProfits::where([
                ['user_id', $data['user_id']],
                ['date', $data['date']]
            ])->first();

            if (empty($row)){

                return (bool)UserProfits::create($data);
            }else{

                return (bool)$row->update($data);
            }
        }
    }
}
