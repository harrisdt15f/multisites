<?php

use Illuminate\Database\Seeder;

class AccountChangeReportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('account_change_reports')->delete();
        
        \DB::table('account_change_reports')->insert(array (
            0 => 
            array (
                'id' => 2,
                'sign' => 'a',
                'user_id' => 1,
                'top_id' => NULL,
                'parent_id' => NULL,
                'rid' => '1',
                'username' => 'harriszhongdai',
                'from_id' => 0,
                'from_admin_id' => 0,
                'to_id' => 0,
                'type_sign' => 'artificial_recharge',
                'type_name' => '人工充值',
                'lottery_id' => NULL,
                'method_id' => NULL,
                'project_id' => 0,
                'issue' => NULL,
                'day' => '2019-05-29',
                'activity_sign' => NULL,
                'amount' => 10,
                'before_balance' => 0,
                'balance' => 10,
                'before_frozen_balance' => 0,
                'frozen_balance' => 0,
                'frozen_type' => 0,
                'is_tester' => 0,
                'process_time' => 0,
                'desc' => '',
                'created_at' => '2019-05-25 15:28:44',
                'updated_at' => '2019-05-25 15:28:44',
            ),
            1 => 
            array (
                'id' => 3,
                'sign' => 'a',
                'user_id' => 1,
                'top_id' => NULL,
                'parent_id' => NULL,
                'rid' => '1',
                'username' => 'harriszhongdai',
                'from_id' => 0,
                'from_admin_id' => 0,
                'to_id' => 0,
                'type_sign' => 'artificial_deduction',
                'type_name' => '人工扣款',
                'lottery_id' => NULL,
                'method_id' => NULL,
                'project_id' => 0,
                'issue' => NULL,
                'day' => '2019-05-29',
                'activity_sign' => NULL,
                'amount' => 10,
                'before_balance' => 10,
                'balance' => 0,
                'before_frozen_balance' => 0,
                'frozen_balance' => 0,
                'frozen_type' => 0,
                'is_tester' => 0,
                'process_time' => 0,
                'desc' => '',
                'created_at' => '2019-05-25 16:35:29',
                'updated_at' => '2019-05-25 16:35:29',
            ),
            2 => 
            array (
                'id' => 5,
                'sign' => 'a',
                'user_id' => 1,
                'top_id' => 0,
                'parent_id' => 0,
                'rid' => '1',
                'username' => 'harriszhongdai',
                'from_id' => 0,
                'from_admin_id' => 0,
                'to_id' => 0,
                'type_sign' => 'bet_cost',
                'type_name' => '投注扣款',
                'lottery_id' => 'cqssc',
                'method_id' => 'ZX5',
                'project_id' => 11,
                'issue' => '190605033',
                'day' => '2019-06-05',
                'activity_sign' => '0',
                'amount' => 40000,
                'before_balance' => 60000,
                'balance' => 20000,
                'before_frozen_balance' => 40000,
                'frozen_balance' => 80000,
                'frozen_type' => 1,
                'is_tester' => 0,
                'process_time' => 1559717402,
                'desc' => '0',
                'created_at' => '2019-06-05 14:50:02',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 6,
                'sign' => 'a',
                'user_id' => 1,
                'top_id' => 0,
                'parent_id' => 0,
                'rid' => '1',
                'username' => 'harriszhongdai',
                'from_id' => 0,
                'from_admin_id' => 0,
                'to_id' => 0,
                'type_sign' => 'artificial_recharge',
                'type_name' => '人工充值',
                'lottery_id' => NULL,
                'method_id' => NULL,
                'project_id' => 0,
                'issue' => NULL,
                'day' => '0000-00-00',
                'activity_sign' => NULL,
                'amount' => 1,
                'before_balance' => 20000,
                'balance' => 20001,
                'before_frozen_balance' => 0,
                'frozen_balance' => 0,
                'frozen_type' => 0,
                'is_tester' => 0,
                'process_time' => 0,
                'desc' => '',
                'created_at' => '2019-06-06 17:46:50',
                'updated_at' => '2019-06-06 17:46:50',
            ),
        ));
        
        
    }
}