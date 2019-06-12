<?php

use Illuminate\Database\Seeder;

class FrontendUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users')->delete();
        
        \DB::table('frontend_users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'harriszhongdai',
                'top_id' => 0,
                'parent_id' => 0,
                'rid' => '1',
                'sign' => 'a',
                'account_id' => 1,
                'type' => 2,
                'vip_level' => 0,
                'is_tester' => 0,
                'frozen_type' => 0,
                'nickname' => 'harriszhongdai',
                'password' => '$2y$10$jviprT2Ej6Q5uUWwt3p..u6dfba3pcduU9xhcLm99r7EnEZmriMxC',
                'fund_password' => '$2y$10$71x11gceU8LOzZbQA47F4OojCJfv2Y3GO3E8rf9rKJQFfSvWXqnFW',
                'prize_group' => 1980,
                'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbC5tdWx0aXNpdGVzLmNvbVwvd2ViLWFwaVw',
                'level_deep' => 0,
                'register_ip' => '172.19.0.1',
                'last_login_ip' => '172.19.0.1',
                'register_time' => NULL,
                'last_login_time' => '2019-06-11 21:29:19',
                'extend_info' => NULL,
                'status' => 1,
                'created_at' => '2019-05-16 11:07:18',
                'updated_at' => '2019-06-11 21:29:19',
            ),
        ));
        
        
    }
}