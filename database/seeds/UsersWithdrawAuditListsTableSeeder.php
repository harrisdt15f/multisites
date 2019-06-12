<?php

use Illuminate\Database\Seeder;

class UsersWithdrawAuditListsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users_withdraw_audit_lists')->delete();
        
        
        
    }
}