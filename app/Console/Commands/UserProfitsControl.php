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
    protected $signature = 'UserProfits {date=today}';

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
        if($this->argument('date') == 'today'){
            $today = Carbon::now()->toDateString();
        }else{
            $today = Carbon::yesterday()->toDateString();
        }

        $todayAccountsReportsUsers = FrontendUsersAccountsReport::where([
            ['created_at', '>', $today],
        ])
            ->select('username','user_id','is_tester','parent_id')
            ->groupby('username')
            ->get();


        if (is_object($todayAccountsReportsUsers)){
            foreach ($todayAccountsReportsUsers as $child){

                $data['team_deposit'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_DEPOSIT_SIGN) ;
                $data['team_withdrawal'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_WITHDRAWAL_SIGN) ;
                $data['team_turnover'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_TURNOVER_SIGN) ;
                $data['team_prize'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_PRIZE_SIGN) ;
                $data['team_commission'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_COMMISSION_SIGN) ;
                $data['team_bet_commission'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_BETCOMMISSION_SIGN) ;
                $data['team_dividend'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_DVIVDEND_SIGN) ;
                $data['team_daily_salary'] = Self::getSumChildProfits($today, $child->user_id, UserProfits::TEAM_DAILYSALARY_SIGN) ;
                $data['team_profit'] = $data['team_prize'] + $data['team_commission'] + $data['team_bet_commission'] - $data['team_turnover'];

                $data['deposit'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_DEPOSIT_SIGN) ;
                $data['withdrawal'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_WITHDRAWAL_SIGN) ;
                $data['turnover'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_TURNOVER_SIGN) ;
                $data['prize'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_PRIZE_SIGN) ;
                $data['commission'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_COMMISSION_SIGN) ;
                $data['bet_commission'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_BETCOMMISSION_SIGN) ;
                $data['dividend'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_DVIVDEND_SIGN) ;
                $data['daily_salary'] = Self::getSumProfits($today, $child->user_id, UserProfits::TEAM_DAILYSALARY_SIGN) ;
                $data['profit'] = $data['prize'] + $data['commission'] + $data['bet_commission'] - $data['turnover'];

                $data['date'] = $today;
                $data['user_id'] =  $child->user_id;
                $data['username'] =  $child->username;
                $data['is_tester'] =  $child->is_tester;
                $data['parent_id'] =  $child->parent_id;

                Self::updateProfits($data);
            }
        }
    }

    public static function getSumProfits(string $date, int $user_id, array $type_sign) : float
    {
        return FrontendUsersAccountsReport::where([
            ['created_at', '>', $date],
            ['user_id', $user_id]
        ])
            ->whereIn('type_sign', $type_sign)
            ->sum('amount');
    }

    public static function getSumChildProfits(string $date, int $parent_id, array $type_sign) : float
    {
        return FrontendUsersAccountsReport::where([
            ['created_at', '>', $date],
            ['parent_id', $parent_id]
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
