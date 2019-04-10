<?php

use Illuminate\Database\Seeder;

class PartnerAdminRouteTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_admin_route')->delete();
        
        \DB::table('partner_admin_route')->insert(array (
            0 => 
            array (
                'id' => 1,
                'route_name' => 'menu.setting',
                'menu_group_id' => 17,
                'title' => '菜单操作',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'route_name' => 'partnerAdminGroup.create',
                'menu_group_id' => 17,
                'title' => 'aaaa',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'route_name' => 'logout',
                'menu_group_id' => NULL,
                'title' => '登出',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'route_name' => 'menu.allPartnerMenu',
                'menu_group_id' => 17,
                'title' => '获取所有菜单',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'route_name' => 'partnerAdminGroup.detail',
                'menu_group_id' => 17,
                'title' => '获取管理员角色接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'route_name' => 'partnerAdminGroup.edit',
                'menu_group_id' => 17,
                'title' => '编辑管理员角色接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'route_name' => 'partnerAdmin.get-all-users',
                'menu_group_id' => 17,
                'title' => '获取所有管理员接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'route_name' => 'partnerAdmin.update-user-group',
                'menu_group_id' => 17,
                'title' => '更改目前用户到另外的组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'route_name' => 'partnerAdmin.register',
                'menu_group_id' => 17,
                'title' => '创建商户用户',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'route_name' => 'partnerAdmin.delete-user',
                'menu_group_id' => 17,
                'title' => '删除管理员账号',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'route_name' => 'partnerAdminGroup.delete-access-group',
                'menu_group_id' => 17,
                'title' => '删除管理员组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'route_name' => 'partnerAdmin.self-reset-password',
                'menu_group_id' => 17,
                'title' => '更换密码管理员亲自',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'route_name' => 'partnerAdminGroup.specific-group-users',
                'menu_group_id' => 17,
                'title' => '获取某组的管理员信息',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}