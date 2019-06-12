<?php

use Illuminate\Database\Seeder;

class UsersWithdrawLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users_withdraw_logs')->delete();
        
        
        
    }
}