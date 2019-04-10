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
                'pid' => 1,
                'sign' => 'min_bet_prize_group',
                'name' => '最低投注奖金组',
                'description' => NULL,
                'value' => '1800',
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'pid' => 1,
                'sign' => 'max_bet_prize_group',
                'name' => '最高投注奖金组',
                'description' => NULL,
                'value' => '1960',
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'pid' => 1,
                'sign' => 'min_user_prize_group',
                'name' => '最低开户奖金组',
                'description' => NULL,
                'value' => '1800',
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'pid' => 1,
                'sign' => 'max_user_prize_group',
                'name' => '最高开户奖金组',
                'description' => NULL,
                'value' => '1960',
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'pid' => 1,
                'sign' => 'max_withdraw',
                'name' => '最大提现次数',
                'description' => NULL,
                'value' => '5000',
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}