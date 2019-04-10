<?php

use Illuminate\Database\Seeder;

class PartnerUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_users')->delete();
        
        \DB::table('partner_users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'test888',
                'email' => 'test888@168.com',
                'group_id' => 8,
                'password' => '$2y$10$.6LM7eazzuauLgKgC5Zc/.PUCVIwfomp7pEglR/v9Z2fX8KMjrfFa',
                'fund_password' => '$2y$10$PSohYnoyvpTyTEz.zfdKO.mebxqQmbZZKKWFKmsqwNwbEZPxrysZ6',
                'sign' => 'Y1',
                'platform_name' => '苍龙娱乐',
                'db_type' => 'table',
                'theme' => 'default',
                'remember_token' => '',
                'register_ip' => '0.0.0.0',
                'last_login_ip' => '',
                'last_login_time' => 0,
                'admin_id' => 0,
                'status' => 1,
                'created_at' => '2019-03-08 19:53:51',
                'updated_at' => '2019-03-08 19:53:51',
            ),
        ));
        
        
    }
}