<?php

use Illuminate\Database\Seeder;

class PartnerPlatformsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_platforms')->delete();
        
        \DB::table('partner_platforms')->insert(array (
            0 => 
            array (
                'id' => 1,
                'sign' => 'Y1',
                'platform_name' => '火狼游戏',
                'db_sign' => 'default',
                'db_name' => 'default_20190412',
                'theme' => 'default',
                'prize_group_min' => 1700,
                'prize_group_max' => 1980,
                'single_price' => 2,
                'open_mode' => '1|0.1|0.01',
                'admin_id' => 0,
                'last_admin_id' => 0,
                'status' => 1,
                'created_at' => '2019-04-12 12:28:59',
                'updated_at' => '2019-04-12 12:28:59',
            ),
            1 => 
            array (
                'id' => 2,
                'sign' => 'Y2',
                'platform_name' => '宝马游戏',
                'db_sign' => 'default',
                'db_name' => 'default_20190412',
                'theme' => 'default',
                'prize_group_min' => 1700,
                'prize_group_max' => 1980,
                'single_price' => 2,
                'open_mode' => '1|0.1|0.01',
                'admin_id' => 0,
                'last_admin_id' => 0,
                'status' => 1,
                'created_at' => '2019-04-12 12:29:14',
                'updated_at' => '2019-04-12 12:29:14',
            ),
        ));
        
        
    }
}