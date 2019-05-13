<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_users')->delete();
        
        \DB::table('admin_users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'tom888',
                'email' => 'tom@zhongxing.com',
                'group_id' => 1,
                'password' => '$2y$10$cKVQYosafZ9iCKVwOu./SuL7N5/fPu0blol/DdgEAZnmyXEOQYIbS',
                'fund_password' => '$2y$10$pYdO7GBiIzqEIWamtl9C0.cm/WhgA55fhIzx1d9sqar7yrTEfsqeq',
                'theme' => 'default',
                'remember_token' => '',
                'register_ip' => '127.0.0.1',
                'last_login_ip' => '116.50.231.34',
                'last_login_time' => 1556604022,
                'admin_id' => 0,
                'status' => 1,
                'created_at' => '2019-04-29 15:21:15',
                'updated_at' => '2019-04-30 14:00:22',
            ),
        ));
        
        
    }
}