<?php

use Illuminate\Database\Seeder;

class UsersRechargeLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users_recharge_logs')->delete();
        
        \DB::table('users_recharge_logs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_order_num' => '201905234541624478fd78a4d',
                'log_num' => '8dc116ffe9db',
                'real_amount' => NULL,
                'deposit_mode' => 1,
                'req_type' => NULL,
                'req_type_1_params' => NULL,
                'req_type_2_params' => NULL,
                'user_recharge_logcol2' => NULL,
                'created_at' => '2019-05-23 17:42:21',
                'updated_at' => '2019-05-23 17:42:21',
            ),
            1 => 
            array (
                'id' => 2,
                'company_order_num' => '20190525928480329084bf16c',
                'log_num' => '8dc4ecf27c4a',
                'real_amount' => NULL,
                'deposit_mode' => 1,
                'req_type' => NULL,
                'req_type_1_params' => NULL,
                'req_type_2_params' => NULL,
                'user_recharge_logcol2' => NULL,
                'created_at' => '2019-05-25 15:28:04',
                'updated_at' => '2019-05-25 15:28:04',
            ),
            2 => 
            array (
                'id' => 3,
                'company_order_num' => '2019060644115278420b36186',
                'log_num' => '8ddd4260a893',
                'real_amount' => NULL,
                'deposit_mode' => 1,
                'req_type' => NULL,
                'req_type_1_params' => NULL,
                'req_type_2_params' => NULL,
                'user_recharge_logcol2' => NULL,
                'created_at' => '2019-06-06 17:46:51',
                'updated_at' => '2019-06-06 17:46:51',
            ),
        ));
        
        
    }
}