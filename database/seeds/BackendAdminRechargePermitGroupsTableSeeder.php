<?php

use Illuminate\Database\Seeder;

class BackendAdminRechargePermitGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_recharge_permit_groups')->delete();
        
        \DB::table('backend_admin_recharge_permit_groups')->insert(array (
            0 => 
            array (
                'id' => 6,
                'group_id' => 2,
                'group_name' => '彩票组',
                'created_at' => '2019-05-11 16:08:56',
                'updated_at' => '2019-05-11 16:08:56',
            ),
            1 => 
            array (
                'id' => 8,
                'group_id' => 3,
                'group_name' => '测试充值组',
                'created_at' => '2019-05-23 17:34:00',
                'updated_at' => '2019-05-23 17:34:00',
            ),
            2 => 
            array (
                'id' => 9,
                'group_id' => 8,
                'group_name' => 'asdasdad',
                'created_at' => '2019-06-05 16:06:22',
                'updated_at' => '2019-06-05 16:06:22',
            ),
        ));
        
        
    }
}