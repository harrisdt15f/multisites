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
                'role' => '[1,2,3,11,59,64,5,9,65,66,67,73,69,70,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,60,28,29,30,32,33,34,35,36,38,40,41,42,61,79,80,97,99,43,44,45,47,48,49,50,51,52,53,54,55,56,57,58,81,82,98,100]',
                'status' => 1,
                'created_at' => '2019-05-11 16:08:56',
                'updated_at' => '2019-05-11 16:08:56',
                'platform_id' => 1,
            ),
        ));
        
        
    }
}