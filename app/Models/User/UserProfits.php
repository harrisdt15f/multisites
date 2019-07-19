<?php
/**
 * 用户团队盈亏 model
 */
namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\User\Fund\FrontendUsersAccountsReport;

class UserProfits extends BaseModel
{
    const TEAM_DEPOSIT_SIGN         = ['recharge','artificial_recharge'];
    const TEAM_WITHDRAWAL_SIGN      = ['withdraw_finis'];
    const TEAM_TURNOVER_SIGN        = ['bet_cost','trace_cost'];
    const TEAM_PRIZE_SIGN           = ['game_bonus'];
    const TEAM_COMMISSION_SIGN      = [];                 //TODO 下级投注返点，因帐变类型暂无 延后
    const TEAM_DVIVDEND_SIGN        = ['gift'];            //促销红利
    const TEAM_DAILYSALARY_SIGN     = ['day_salary'];       //日工资


    protected $guarded = ['id'];


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
            $row = self::where([
                ['user_id', $data['user_id']],
                ['date', $data['date']]
            ])->first();

            if (empty($row)){

                return (bool)self::create($data);
            }else{

                return (bool)$row->update($data);
            }
        }
    }
}
