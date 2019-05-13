<?php

use Illuminate\Database\Seeder;

class PartnerAdminGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_admin_groups')->delete();
        
        \DB::table('partner_admin_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'pid' => 0,
                'rid' => '1',
                'name' => '超级管理员',
                'level' => 1,
                'platform_sign' => 'Y1',
                'member_count' => 1,
                'acl' => '*',
                'created_at' => '2019-04-29 15:22:29',
                'updated_at' => '2019-04-29 15:22:29',
            ),
            1 => 
            array (
                'id' => 2,
                'pid' => 1,
                'rid' => '1|2',
                'name' => '运营经理',
                'level' => 2,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:30',
                'updated_at' => '2019-04-29 15:22:30',
            ),
            2 => 
            array (
                'id' => 3,
                'pid' => 2,
                'rid' => '1|2|3',
                'name' => '运营主管',
                'level' => 3,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:30',
                'updated_at' => '2019-04-29 15:22:31',
            ),
            3 => 
            array (
                'id' => 4,
                'pid' => 3,
                'rid' => '1|2|3|4',
                'name' => '运营专员',
                'level' => 4,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:31',
                'updated_at' => '2019-04-29 15:22:31',
            ),
            4 => 
            array (
                'id' => 5,
                'pid' => 1,
                'rid' => '1|5',
                'name' => '市场经理',
                'level' => 2,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:31',
                'updated_at' => '2019-04-29 15:22:32',
            ),
            5 => 
            array (
                'id' => 6,
                'pid' => 5,
                'rid' => '1|5|6',
                'name' => '市场主管',
                'level' => 3,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:32',
                'updated_at' => '2019-04-29 15:22:32',
            ),
            6 => 
            array (
                'id' => 7,
                'pid' => 6,
                'rid' => '1|5|6|7',
                'name' => '业务专员',
                'level' => 4,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:32',
                'updated_at' => '2019-04-29 15:22:33',
            ),
            7 => 
            array (
                'id' => 8,
                'pid' => 1,
                'rid' => '1|8',
                'name' => '财务经理',
                'level' => 2,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:33',
                'updated_at' => '2019-04-29 15:22:33',
            ),
            8 => 
            array (
                'id' => 9,
                'pid' => 8,
                'rid' => '1|8|9',
                'name' => '财务主管',
                'level' => 3,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:33',
                'updated_at' => '2019-04-29 15:22:34',
            ),
            9 => 
            array (
                'id' => 10,
                'pid' => 9,
                'rid' => '1|8|9|10',
                'name' => '财务专员',
                'level' => 4,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:34',
                'updated_at' => '2019-04-29 15:22:34',
            ),
            10 => 
            array (
                'id' => 11,
                'pid' => 1,
                'rid' => '1|11',
                'name' => '风控经理',
                'level' => 2,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:35',
                'updated_at' => '2019-04-29 15:22:35',
            ),
            11 => 
            array (
                'id' => 12,
                'pid' => 11,
                'rid' => '1|11|12',
                'name' => '风控主管',
                'level' => 3,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:35',
                'updated_at' => '2019-04-29 15:22:35',
            ),
            12 => 
            array (
                'id' => 13,
                'pid' => 12,
                'rid' => '1|11|12|13',
                'name' => '风控专员',
                'level' => 4,
                'platform_sign' => 'Y1',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:36',
                'updated_at' => '2019-04-29 15:22:36',
            ),
            13 => 
            array (
                'id' => 14,
                'pid' => 0,
                'rid' => '14',
                'name' => '超级管理员',
                'level' => 1,
                'platform_sign' => 'Y2',
                'member_count' => 1,
                'acl' => '*',
                'created_at' => '2019-04-29 15:22:46',
                'updated_at' => '2019-04-29 15:22:47',
            ),
            14 => 
            array (
                'id' => 15,
                'pid' => 14,
                'rid' => '14|15',
                'name' => '运营经理',
                'level' => 2,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:47',
                'updated_at' => '2019-04-29 15:22:47',
            ),
            15 => 
            array (
                'id' => 16,
                'pid' => 15,
                'rid' => '14|15|16',
                'name' => '运营主管',
                'level' => 3,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:48',
                'updated_at' => '2019-04-29 15:22:48',
            ),
            16 => 
            array (
                'id' => 17,
                'pid' => 16,
                'rid' => '14|15|16|17',
                'name' => '运营专员',
                'level' => 4,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:48',
                'updated_at' => '2019-04-29 15:22:48',
            ),
            17 => 
            array (
                'id' => 18,
                'pid' => 14,
                'rid' => '14|18',
                'name' => '市场经理',
                'level' => 2,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:49',
                'updated_at' => '2019-04-29 15:22:49',
            ),
            18 => 
            array (
                'id' => 19,
                'pid' => 18,
                'rid' => '14|18|19',
                'name' => '市场主管',
                'level' => 3,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:49',
                'updated_at' => '2019-04-29 15:22:49',
            ),
            19 => 
            array (
                'id' => 20,
                'pid' => 19,
                'rid' => '14|18|19|20',
                'name' => '业务专员',
                'level' => 4,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:50',
                'updated_at' => '2019-04-29 15:22:50',
            ),
            20 => 
            array (
                'id' => 21,
                'pid' => 14,
                'rid' => '14|21',
                'name' => '财务经理',
                'level' => 2,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:50',
                'updated_at' => '2019-04-29 15:22:50',
            ),
            21 => 
            array (
                'id' => 22,
                'pid' => 21,
                'rid' => '14|21|22',
                'name' => '财务主管',
                'level' => 3,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:51',
                'updated_at' => '2019-04-29 15:22:51',
            ),
            22 => 
            array (
                'id' => 23,
                'pid' => 22,
                'rid' => '14|21|22|23',
                'name' => '财务专员',
                'level' => 4,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:51',
                'updated_at' => '2019-04-29 15:22:51',
            ),
            23 => 
            array (
                'id' => 24,
                'pid' => 14,
                'rid' => '14|24',
                'name' => '风控经理',
                'level' => 2,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:52',
                'updated_at' => '2019-04-29 15:22:52',
            ),
            24 => 
            array (
                'id' => 25,
                'pid' => 24,
                'rid' => '14|24|25',
                'name' => '风控主管',
                'level' => 3,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:52',
                'updated_at' => '2019-04-29 15:22:52',
            ),
            25 => 
            array (
                'id' => 26,
                'pid' => 25,
                'rid' => '14|24|25|26',
                'name' => '风控专员',
                'level' => 4,
                'platform_sign' => 'Y2',
                'member_count' => 0,
                'acl' => '',
                'created_at' => '2019-04-29 15:22:53',
                'updated_at' => '2019-04-29 15:22:53',
            ),
        ));
        
        
    }
}