<?php

use Illuminate\Database\Seeder;

class PartnerSysConfiguresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_sys_configures')->delete();
        
        \DB::table('partner_sys_configures')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'system',
                'name' => '系统相关',
                'description' => '所有系统相关配置都保存此处',
                'value' => NULL,
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 14:49:03',
                'updated_at' => '2019-05-06 14:49:03',
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'min_bet_prize_group',
                'name' => '最低投注奖金组',
                'description' => '最低投注奖金组',
                'value' => '1800',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 14:50:34',
                'updated_at' => '2019-05-06 14:50:34',
            ),
            2 => 
            array (
                'id' => 3,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'max_bet_prize_group',
                'name' => '最高投注奖金组',
                'description' => '最高投注奖金组',
                'value' => '1960',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 15:25:13',
                'updated_at' => '2019-05-06 15:25:13',
            ),
            3 => 
            array (
                'id' => 4,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'min_user_prize_group',
                'name' => '最低开户奖金组',
                'description' => '最低开户奖金组',
                'value' => '1800',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 15:25:45',
                'updated_at' => '2019-05-06 15:25:45',
            ),
            4 => 
            array (
                'id' => 5,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'max_user_prize_group',
                'name' => '最高开户奖金组',
                'description' => '最高开户奖金组',
                'value' => '1960',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 15:26:59',
                'updated_at' => '2019-05-06 15:26:59',
            ),
            5 => 
            array (
                'id' => 6,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'max_withdraw',
                'name' => '最大提现次数',
                'description' => '最大提现次数',
                'value' => '5000',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 15:27:25',
                'updated_at' => '2019-05-06 15:27:25',
            ),
            6 => 
            array (
                'id' => 8,
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'admin_fund_control',
                'name' => '管理员金额限制',
                'description' => '管理员金额限制',
                'value' => NULL,
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 15:31:52',
                'updated_at' => '2019-05-06 15:31:52',
            ),
            7 => 
            array (
                'id' => 9,
                'parent_id' => 8,
                'pid' => 1,
                'sign' => 'admin_recharge_daily_limit',
                'name' => '每日管理员充值限制金额',
                'description' => '每日管理员充值限制金额',
                'value' => '10000',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'created_at' => '2019-05-06 15:32:26',
                'updated_at' => '2019-05-11 15:48:06',
            ),
        ));
        
        
    }
}