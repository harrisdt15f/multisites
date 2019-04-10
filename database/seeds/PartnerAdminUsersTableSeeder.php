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
                'id' => 9,
                'name' => '11',
                'email' => 'uo@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$QIaB74Arlf6JLGBqNE5I4e54SjFUm7M..priSSAcw20iK1dinQIrW',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 18,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-05 11:59:16',
                'updated_at' => '2019-04-05 11:59:16',
            ),
            3 => 
            array (
                'id' => 10,
                'name' => '2131',
                'email' => 'dd@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$QY7Y1Zb41cK98l7GTtuqDOF0hdLAUc8vPKWWoDf3yNrmFMf.TAnWm',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 18,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-05 12:01:04',
                'updated_at' => '2019-04-09 17:29:00',
            ),
            4 => 
            array (
                'id' => 11,
                'name' => '11',
                'email' => '11@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$./BEdQUuS3nAzhw3huXPjeoCQ29dwItsTas1.aszaav6Udzeubg/W',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 17,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-08 20:02:37',
                'updated_at' => '2019-04-08 20:02:37',
            ),
            5 => 
            array (
                'id' => 12,
                'name' => '11',
                'email' => '11@11.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$khlWOfE6Uwp5r5goNUS5Aur8NcQwM2IHQHb1GsZldagFC92R0fRXa',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-08 20:24:00',
                'updated_at' => '2019-04-08 20:24:00',
            ),
            6 => 
            array (
                'id' => 13,
                'name' => 'york1',
                'email' => 'york1@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$5tkE4l28qdnJuwxsnY411OPCvEstV/OtFGh7cvNEZFSXHiM0PaD6.',
                'remember_token' => NULL,
                'is_test' => 0,
                'group_id' => 18,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-08 20:26:48',
                'updated_at' => '2019-04-09 17:29:35',
            ),
            7 => 
            array (
                'id' => 14,
                'name' => 'york1',
                'email' => 'york1@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$P7Na1WOvlVKQLKGjbCb9J.ZNiioJsdxiZKuSiu3i/qICPjTZm38Yq',
                'remember_token' => NULL,
                'is_test' => 0,
                'group_id' => 18,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-08 20:27:43',
                'updated_at' => '2019-04-09 17:28:47',
            ),
            8 => 
            array (
                'id' => 15,
                'name' => 'york1',
                'email' => 'york1@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$IAJus7Y/KPEllVAIkghbH..AWXSav5phe9M0a2So1uc9IUS5Bi5MO',
                'remember_token' => NULL,
                'is_test' => 0,
                'group_id' => 18,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-08 20:27:50',
                'updated_at' => '2019-04-09 17:29:43',
            ),
            9 => 
            array (
                'id' => 16,
                'name' => '测试管理员',
                'email' => '1@hh.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$mKnYZQaUtr9lCf.hc.eOauZnQoenTHtVgEltGsgZOvhzoxISYm6kO',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-09 18:32:50',
                'updated_at' => '2019-04-09 18:32:50',
            ),
        ));
        
        
    }
}