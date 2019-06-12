<?php

use Illuminate\Database\Seeder;

class BackendAdminAccessGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_access_groups')->delete();
        
        \DB::table('backend_admin_access_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'group_name' => '超级管理组',
                'role' => '*',
                'status' => 1,
                'created_at' => '2019-04-10 10:10:56',
                'updated_at' => '2019-04-10 10:10:51',
                'platform_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'group_name' => '彩票组',
                'role' => '[1,2,3,11,59,64,5,9,65,66,73,69,70,103,104,105,106,107,108,109,110,112,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,60,28,29,30,32,33,34,35,36,38,40,41,42,61,79,80,97,99,43,44,45]',
                'status' => 1,
                'created_at' => '2019-06-05 15:44:28',
                'updated_at' => '2019-06-05 15:44:28',
                'platform_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'group_name' => '测试充值组',
                'role' => '[1,2,3,11,59,64,5,9,65,66,67,73,69,70,103,104,105,106,107,108,109,110,25,26,27,60,28,29,30,32,33,34,35,36,38,40,41,42,61,79,80,97,99,43,44,45]',
                'status' => 1,
                'created_at' => '2019-05-23 17:34:00',
                'updated_at' => '2019-05-23 17:34:00',
                'platform_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'group_name' => '1',
                'role' => '[1,2,3,11,59,64,5,9,65,66,73,69,70,103,104,105,106,107,108,109,110,112]',
                'status' => 1,
                'created_at' => '2019-06-05 16:05:02',
                'updated_at' => '2019-06-05 16:05:02',
                'platform_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'group_name' => '2',
                'role' => '[1,2,3,11,59,64,5,9,65,66,73,69,70,103,104,105,106,107,108,109,110,112]',
                'status' => 1,
                'created_at' => '2019-06-05 16:05:18',
                'updated_at' => '2019-06-05 16:05:18',
                'platform_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'group_name' => '1问问',
                'role' => '[1,2,3,11,59,64,5,9,65,66,73,69,70,103,104,105,106,107,108,109,110,112]',
                'status' => 1,
                'created_at' => '2019-06-05 16:05:34',
                'updated_at' => '2019-06-05 16:05:34',
                'platform_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'group_name' => 'qweqwe',
                'role' => '[12,13,14,15,16,17,18,19,20,21,22,23,24]',
                'status' => 1,
                'created_at' => '2019-06-05 16:05:43',
                'updated_at' => '2019-06-05 16:05:43',
                'platform_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'group_name' => 'asdasdad',
                'role' => '[25,26,27,60,28,29,30,32,33,34,35,36,38,40,41,42,61,79,80,97,99]',
                'status' => 1,
                'created_at' => '2019-06-05 16:06:22',
                'updated_at' => '2019-06-05 16:06:22',
                'platform_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'group_name' => '77',
                'role' => '[47,48,49,50,51,52,53,54,55,56,57,58]',
                'status' => 1,
                'created_at' => '2019-06-05 16:08:59',
                'updated_at' => '2019-06-05 16:08:59',
                'platform_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'group_name' => '5566',
                'role' => '[47,48,49,50,51,52,53,54,55,56,57,58]',
                'status' => 1,
                'created_at' => '2019-06-05 16:09:13',
                'updated_at' => '2019-06-05 16:09:13',
                'platform_id' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'group_name' => 'ghgh',
                'role' => '[1,2,3,11,59,64,5,9,65,66,73,69,70,103,104,105,106,107,108,109,110,112]',
                'status' => 1,
                'created_at' => '2019-06-05 16:09:26',
                'updated_at' => '2019-06-05 16:09:26',
                'platform_id' => 1,
            ),
        ));
        
        
    }
}