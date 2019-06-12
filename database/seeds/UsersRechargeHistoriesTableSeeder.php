<?php

use Illuminate\Database\Seeder;

class UsersRechargeHistoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users_recharge_histories')->delete();
        
        \DB::table('users_recharge_histories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'user_name' => 'harriszhongdai',
                'is_tester' => 0,
                'top_agent' => NULL,
                'channel' => NULL,
                'payment_id' => NULL,
                'amount' => '10.00',
                'company_order_num' => '201905234541624478fd78a4d',
                'third_party_order_num' => NULL,
                'deposit_mode' => 1,
                'real_amount' => NULL,
                'fee' => NULL,
                'audit_flow_id' => 257,
                'status' => 12,
                'updated_at' => '2019-05-23 17:45:10',
                'created_at' => '2019-05-23 17:42:21',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'user_name' => 'harriszhongdai',
                'is_tester' => 0,
                'top_agent' => NULL,
                'channel' => NULL,
                'payment_id' => NULL,
                'amount' => '10.00',
                'company_order_num' => '20190525928480329084bf16c',
                'third_party_order_num' => NULL,
                'deposit_mode' => 1,
                'real_amount' => NULL,
                'fee' => NULL,
                'audit_flow_id' => 258,
                'status' => 11,
                'updated_at' => '2019-05-25 15:28:44',
                'created_at' => '2019-05-25 15:28:04',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'user_name' => 'harriszhongdai',
                'is_tester' => 0,
                'top_agent' => 0,
                'channel' => NULL,
                'payment_id' => NULL,
                'amount' => '1.00',
                'company_order_num' => '2019060644115278420b36186',
                'third_party_order_num' => NULL,
                'deposit_mode' => 1,
                'real_amount' => NULL,
                'fee' => NULL,
                'audit_flow_id' => NULL,
                'status' => 11,
                'updated_at' => '2019-06-06 17:46:51',
                'created_at' => '2019-06-06 17:46:51',
            ),
        ));
        
        
    }
}