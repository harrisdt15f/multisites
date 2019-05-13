<?php

use Illuminate\Database\Seeder;

class AdminGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_groups')->delete();
        
        \DB::table('admin_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'pid' => 0,
                'rid' => '1',
                'name' => '超级管理员',
                'member_count' => 1,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:15',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'pid' => 1,
                'rid' => '1|2',
                'name' => '运营经理',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:15',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'pid' => 2,
                'rid' => '1|2|3',
                'name' => '运营主管',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:16',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'pid' => 3,
                'rid' => '1|2|3|4',
                'name' => '运营专员',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:16',
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'pid' => 1,
                'rid' => '1|5',
                'name' => '市场经理',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:16',
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'pid' => 5,
                'rid' => '1|5|6',
                'name' => '市场主管',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:17',
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'pid' => 6,
                'rid' => '1|5|6|7',
                'name' => '业务员',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:17',
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'pid' => 1,
                'rid' => '1|8',
                'name' => '开发经理',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:18',
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'pid' => 8,
                'rid' => '1|8|9',
                'name' => '开发主管',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:18',
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'pid' => 9,
                'rid' => '1|8|9|10',
                'name' => '程序员',
                'member_count' => 0,
                'acl' => '*',
                'created_at' => '2019-04-29 15:21:18',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}