<?php

use Illuminate\Database\Seeder;

class UsersWithdrawHistoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users_withdraw_histories')->delete();
        
        
        
    }
}