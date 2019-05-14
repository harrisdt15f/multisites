<?php

use Illuminate\Database\Seeder;

class PartnerAdminUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_admin_users')->delete();
        
        \DB::table('partner_admin_users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Harris',
                'email' => 'harrisdt15f@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$d93HN92FAs/r/7/iISYwLOeC9aOpSXz5lF.KKDWtOTJkvmvE0omJK',
                'remember_token' => 'ezqRjJTlz4YekPo9O9FFzLL32PK8GMCqgfSufylgermOgDXFnAthuuQIUmkV',
                'is_test' => 0,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-03-29 23:50:58',
                'updated_at' => '2019-04-08 15:53:39',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'york',
                'email' => 'york@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$.otGn0zTx.OvtmmXQOu2neJEUcsm3li4Zt6pIBe1Tsw.qPSqk9.X6',
                'remember_token' => NULL,
                'is_test' => 0,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-04 12:49:23',
                'updated_at' => '2019-04-09 17:09:28',
            ),
            2 => 
            array (
                'id' => 20,
                'name' => 'name',
                'email' => 'qwe@qq.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$KIzu8fdYd1axelRlEUr74e0ahgSxNH052RVS58DlSp4so91XVfw/O',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 2,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-17 16:47:42',
                'updated_at' => '2019-04-17 16:47:42',
            ),
            3 => 
            array (
                'id' => 23,
                'name' => 'Diana',
                'email' => 'Diana@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$6/uQKe9atqCN453SVnLkme6mbWywj0ootoXPf55BB87uCtVAJXTYC',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-05-09 18:13:56',
                'updated_at' => '2019-05-10 16:48:45',
            ),
        ));
        
        
    }
}