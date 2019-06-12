<?php

use Illuminate\Database\Seeder;

class BackendAdminRechargePocessAmountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_recharge_pocess_amounts')->delete();
        
        \DB::table('backend_admin_recharge_pocess_amounts')->insert(array (
            0 => 
            array (
                'id' => 8,
                'admin_id' => 27,
                'fund' => '0.00',
                'created_at' => '2019-05-10 16:16:06',
                'updated_at' => '2019-05-10 16:16:06',
            ),
            1 => 
            array (
                'id' => 9,
                'admin_id' => 28,
                'fund' => '0.00',
                'created_at' => '2019-05-10 16:18:54',
                'updated_at' => '2019-05-10 16:18:54',
            ),
            2 => 
            array (
                'id' => 10,
                'admin_id' => 13,
                'fund' => '0.00',
                'created_at' => '2019-05-11 16:08:56',
                'updated_at' => '2019-05-11 16:08:56',
            ),
            3 => 
            array (
                'id' => 11,
                'admin_id' => 20,
                'fund' => '10000.00',
                'created_at' => '2019-05-11 16:08:56',
                'updated_at' => '2019-05-23 17:45:10',
            ),
            4 => 
            array (
                'id' => 13,
                'admin_id' => 25,
                'fund' => '80000.00',
                'created_at' => '2019-05-23 17:34:00',
                'updated_at' => '2019-05-28 00:00:11',
            ),
            5 => 
            array (
                'id' => 14,
                'admin_id' => 26,
                'fund' => '80000.00',
                'created_at' => '2019-05-23 17:36:47',
                'updated_at' => '2019-05-25 00:00:15',
            ),
        ));
        
        
    }
}