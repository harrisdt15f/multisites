<?php

use Illuminate\Database\Seeder;

class FrontendUsersPrivacyFlowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users_privacy_flows')->delete();
        
        \DB::table('frontend_users_privacy_flows')->insert(array (
            0 => 
            array (
                'id' => 1,
                'admin_id' => 23,
                'admin_name' => 'Diana',
                'user_id' => 502,
                'username' => '啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-05-11 18:53:54',
                'created_at' => '2019-05-11 18:53:54',
            ),
        ));
        
        
    }
}