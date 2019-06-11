<?php

use Illuminate\Database\Seeder;

class BackendAdminUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_users')->delete();
        
        \DB::table('backend_admin_users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Harris',
                'email' => 'harrisdt15f@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Bz7/W8LEMgHnOkAtULbpbOjpjESkTihGyGJLJUsPGYquBJCP8bQfm',
                'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbC5tdWx0aXNpdGVzLmNvbVwvYXBpXC9sb2dpbiIsImlhdCI6MTU2MDEzNzQxMywiZXhwIjoxNTYwMjIzODEzLCJuYmYiOjE1NjAxMzc0MTMsImp0aSI6InE1eUl2cTlGdTNaeWVYaW8iLCJzdWIiOjEsInBydiI6Ijg0MTk3MjRlYTc0YmM1NDMxOTI5MGYwNzQ5NTRjZDdkOTgzMGM5MjcifQ.M04QoKWy6IMs46z9Vd8vLTWG13EQha87A1SWbNHNDck',
                'is_test' => 0,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-03-29 23:50:58',
                'updated_at' => '2019-06-10 11:30:13',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'york',
                'email' => 'york@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$.otGn0zTx.OvtmmXQOu2neJEUcsm3li4Zt6pIBe1Tsw.qPSqk9.X6',
                'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbC5tdWx0aXNpdGVzLmNvbVwvYXBpXC9sb2dpbiIsImlhdCI6MTU2MDE0OTA2NSwiZXhwIjoxNTYwMjM1NDY1LCJuYmYiOjE1NjAxNDkwNjUsImp0aSI6InVBSU5McDkyQVZPcjRaNkEiLCJzdWIiOjQsInBydiI6Ijg0MTk3MjRlYTc0YmM1NDMxOTI5MGYwNzQ5NTRjZDdkOTgzMGM5MjcifQ.iVLaIWH-8cUpZeExHt8dmDlik4C9sIPF5b7Z4Eq5SR8',
                'is_test' => 0,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-04-04 12:49:23',
                'updated_at' => '2019-06-10 14:44:26',
            ),
            2 => 
            array (
                'id' => 23,
                'name' => 'Diana',
                'email' => 'Diana@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$j.ZcLO3yOJYBJxQCLVQjiOUBXqA4oQdvnI9tXHITZIjq6bwT3Wsf6',
                'remember_token' => NULL,
                'is_test' => 1,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-05-09 18:13:56',
                'updated_at' => '2019-05-23 15:36:54',
            ),
            3 => 
            array (
                'id' => 24,
                'name' => 'Ling',
                'email' => 'Ling@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$HgCC6p.x14GUT.jDMwGb3uTXurmpVeyjHNSbr6WZvNdHMGpItMtfm',
                'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbC5tdWx0aXNpdGVzLmNvbVwvYXBpXC9sb2dpbiIsImlhdCI6MTU2MDE0OTQ3MSwiZXhwIjoxNTYwMjM1ODcxLCJuYmYiOjE1NjAxNDk0NzEsImp0aSI6ImJMWk9GQjljNjE1emh5dVciLCJzdWIiOjI0LCJwcnYiOiI4NDE5NzI0ZWE3NGJjNTQzMTkyOTBmMDc0OTU0Y2Q3ZDk4MzBjOTI3In0.zH7T7mgtakgedZauejY9vHeDAM0ExA9zE796CbAfuO0',
                'is_test' => 1,
                'group_id' => 1,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-05-15 11:09:20',
                'updated_at' => '2019-06-10 14:51:11',
            ),
            4 => 
            array (
                'id' => 25,
                'name' => 'york222',
                'email' => 'yo@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$4mTWO.IeazdAQMbxN8isqOO.xjxiUXXp9McHq1/L1n0tcNdyvooCu',
                'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbC5tdWx0aXNpdGVzLmNvbVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1ODc2OTIwOSwiZXhwIjoxNTU4ODU1NjA5LCJuYmYiOjE1NTg3NjkyMDksImp0aSI6ImUzUXZ5WFJGSG9uQUlmaVQiLCJzdWIiOjI1LCJwcnYiOiI1YTBkNjE2MmIwNmE5ZTJjNzM0NDdkNDZlZjQxMTM0OTEwMDA3NWZlIn0.H-jOwpWlyYW0mBMpY79Pu5KQzFoFrIWi34UGMEBBRXw',
                'is_test' => 1,
                'group_id' => 3,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-05-23 15:26:52',
                'updated_at' => '2019-05-25 15:26:49',
            ),
            5 => 
            array (
                'id' => 26,
                'name' => 'york888',
                'email' => 'york888@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$lG7QBo2DsunndtOpnU0AtextDDzCBX.9vlchrjRtedypyKTye/Ada',
                'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbC5tdWx0aXNpdGVzLmNvbVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1ODYwNDM5MCwiZXhwIjoxNTU4NjkwNzkwLCJuYmYiOjE1NTg2MDQzOTAsImp0aSI6ImNNTENTS2xKUFNaWHY5WWciLCJzdWIiOjI2LCJwcnYiOiJkZGIyMTUwMjI0NjdmZmFlMTViMTNlMTU2NWQwZDc2NzJlYzViMmM2In0.7We0Haw5EF7-IETHzdXaRYlNZJeExcwoiwAfOU9BL7k',
                'is_test' => 1,
                'group_id' => 3,
                'status' => 1,
                'platform_id' => 1,
                'super_id' => NULL,
                'created_at' => '2019-05-23 17:36:47',
                'updated_at' => '2019-05-23 17:39:50',
            ),
        ));
        
        
    }
}