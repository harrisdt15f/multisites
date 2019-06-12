<?php

use Illuminate\Database\Seeder;

class FrontendUsersAccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users_accounts')->delete();
        
        \DB::table('frontend_users_accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'balance' => 20001,
                'frozen' => 80000,
                'status' => 1,
                'created_at' => '2019-05-16 11:07:18',
                'updated_at' => '2019-06-06 17:46:49',
            ),
        ));
        
        
    }
}