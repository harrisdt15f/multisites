<?php

use Illuminate\Database\Seeder;

class PartnerAccessGroupTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_access_group')->delete();
        
        \DB::table('partner_access_group')->insert(array (
            0 => 
            array (
                'id' => 1,
                'group_name' => '超级管理组',
                'role' => '*',
                'status' => 1,
                'created_at' => '2019-04-10 10:10:56',
                'updated_at' => '2019-04-10 10:10:51',
                'platform_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'group_name' => '彩票组',
                'role' => '[1,2,3,4,5]',
                'status' => 1,
                'created_at' => '2019-04-04 15:28:13',
                'updated_at' => '2019-04-04 15:28:13',
                'platform_id' => 1,
            ),
            2 => 
            array (
                'id' => 17,
                'group_name' => '222',
                'role' => '[46,47,48,49,50,51,52]',
                'status' => 1,
                'created_at' => '2019-04-08 19:16:22',
                'updated_at' => '2019-04-08 19:16:22',
                'platform_id' => 1,
            ),
            3 => 
            array (
                'id' => 18,
                'group_name' => '213213',
                'role' => '[53,54,55,56,57,46,47,48,49,50,51,52]',
                'status' => 1,
                'created_at' => '2019-04-08 19:16:47',
                'updated_at' => '2019-04-08 19:16:47',
                'platform_id' => 1,
            ),
        ));
        
        
    }
}