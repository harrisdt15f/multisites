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
                'menu_group_id' => 1,
                'title' => '菜单操作',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'route_name' => 'partnerAdminGroup.create',
                'menu_group_id' => 1,
                'title' => 'aaa',
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
                'menu_group_id' => 1,
                'title' => '获取所有菜单',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'route_name' => 'partnerAdminGroup.detail',
                'menu_group_id' => 1,
                'title' => '获取管理员角色接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'route_name' => 'partnerAdminGroup.edit',
                'menu_group_id' => 1,
                'title' => '编辑管理员角色接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'route_name' => 'partnerAdmin.get-all-users',
                'menu_group_id' => 1,
                'title' => '获取所有管理员接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'route_name' => 'partnerAdmin.update-user-group',
                'menu_group_id' => 1,
                'title' => '更改目前用户到另外的组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'route_name' => 'partnerAdmin.register',
                'menu_group_id' => 1,
                'title' => '创建商户用户',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'route_name' => 'partnerAdmin.delete-user',
                'menu_group_id' => 1,
                'title' => '删除管理员账号',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'route_name' => 'partnerAdminGroup.delete-access-group',
                'menu_group_id' => 1,
                'title' => '删除管理员组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'route_name' => 'partnerAdmin.self-reset-password',
                'menu_group_id' => 1,
                'title' => '更换密码管理员亲自',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'route_name' => 'partnerAdminGroup.specific-group-users',
                'menu_group_id' => 1,
                'title' => '获取某组的管理员信息',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'route_name' => 'userhandle.create-user',
                'menu_group_id' => 25,
                'title' => '创建总代接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'route_name' => 'userhandle.prizegroup',
                'menu_group_id' => 25,
                'title' => '创建总代时获取平台的最低与最高的奖金组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'route_name' => 'userhandle.users-info',
                'menu_group_id' => 25,
                'title' => '用户信息表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'route_name' => 'userhandle.reset-password',
                'menu_group_id' => 25,
                'title' => '申请用户密码功能',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'route_name' => 'userhandle.reset-password-list',
                'menu_group_id' => 25,
                'title' => '用户密码已申请列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'route_name' => 'userhandle.reset-fund-password',
                'menu_group_id' => 25,
                'title' => '申请用户资金密码功能',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'route_name' => 'userhandle.reset-fund-password-list',
                'menu_group_id' => 25,
                'title' => '用户资金密码已申请列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'route_name' => 'userhandle.audit-applied-password',
                'menu_group_id' => 25,
                'title' => '给用户审核密码',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'route_name' => 'userhandle.audit-applied-fund-password',
                'menu_group_id' => 25,
                'title' => '给用户审核资金密码',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'route_name' => 'loghandle.list',
                'menu_group_id' => 1,
                'title' => '管理员操作日志列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'route_name' => 'userhandle.deactivate',
                'menu_group_id' => 25,
                'title' => '玩家冻结操作',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'route_name' => 'userhandle.deactivated-detail',
                'menu_group_id' => 25,
                'title' => '玩家被冻结操作历史',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'route_name' => 'menu.current-admin-menu',
                'menu_group_id' => 1,
                'title' => '获取当前商户用户的菜单',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'route_name' => 'configures.detail',
                'menu_group_id' => 3,
                'title' => '获取系统配置',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'route_name' => 'configures.add',
                'menu_group_id' => 3,
                'title' => '添加系统配置',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'route_name' => 'lotteries.series-lists',
                'menu_group_id' => 12,
                'title' => '游戏series获取接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'route_name' => 'lotteries.lotteries-lists',
                'menu_group_id' => 12,
                'title' => '彩种列表获取接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'route_name' => 'configures.edit',
                'menu_group_id' => 3,
                'title' => '修改系统配置',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'route_name' => 'configures.delete',
                'menu_group_id' => 3,
                'title' => '删除系统配置',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'route_name' => 'configures.switch',
                'menu_group_id' => 3,
                'title' => '系统配置开关',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'route_name' => 'lotteries.lotteries-method-lists',
                'menu_group_id' => 12,
                'title' => '彩种玩法展示接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'route_name' => 'region.detail',
                'menu_group_id' => 61,
                'title' => '获取省-市-县列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'route_name' => 'region.get_town',
                'menu_group_id' => 61,
                'title' => '获取镇级行政区',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'route_name' => 'region.search_town',
                'menu_group_id' => 61,
                'title' => '查询镇级行政区',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'route_name' => 'region.add',
                'menu_group_id' => 61,
                'title' => '添加行政区',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'route_name' => 'region.edit',
                'menu_group_id' => 61,
                'title' => '编辑行政区',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'route_name' => 'region.add',
                'menu_group_id' => 61,
                'title' => '添加行政区',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'route_name' => 'region.edit',
                'menu_group_id' => 61,
                'title' => '编辑行政区',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'route_name' => 'activity.add',
                'menu_group_id' => 5,
                'title' => '添加活动',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'route_name' => 'activity.edit',
                'menu_group_id' => 5,
                'title' => '编辑活动',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'route_name' => 'activity.delete',
                'menu_group_id' => 5,
                'title' => '删除活动',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'route_name' => 'activity.detail',
                'menu_group_id' => 5,
                'title' => '活动列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'route_name' => 'activity.type',
                'menu_group_id' => 5,
                'title' => '活动类型列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'route_name' => 'lotteries.lotteries-issue-lists',
                'menu_group_id' => 12,
                'title' => '奖期展示接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 51,
                'route_name' => 'content.category',
                'menu_group_id' => 1,
                'title' => '分类管理',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 52,
                'route_name' => 'lotteries.lotteries-issue-generate',
                'menu_group_id' => 12,
                'title' => '奖期生成接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 53,
                'route_name' => 'content.category',
                'menu_group_id' => 65,
                'title' => '分类列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 54,
                'route_name' => 'content.category-select',
                'menu_group_id' => 65,
                'title' => '文章分类列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 55,
                'route_name' => 'content.detail',
                'menu_group_id' => 66,
                'title' => '文章列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 56,
                'route_name' => 'content.add-articles',
                'menu_group_id' => 66,
                'title' => '发布文章',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 57,
                'route_name' => 'content.edit-articles',
                'menu_group_id' => 66,
                'title' => '编辑文章',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 58,
                'route_name' => 'content.delete-articles',
                'menu_group_id' => 66,
                'title' => '删除文章',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 59,
                'route_name' => 'content.sort-articles',
                'menu_group_id' => 66,
                'title' => '文章排序',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 60,
                'route_name' => 'content.top-articles',
                'menu_group_id' => 66,
                'title' => '置顶文章',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 61,
                'route_name' => 'content.upload-pic',
                'menu_group_id' => 66,
                'title' => '图片上传',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 62,
                'route_name' => 'bank.detail',
                'menu_group_id' => 69,
                'title' => '银行列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 63,
                'route_name' => 'bank.add-bank',
                'menu_group_id' => 69,
                'title' => '添加银行',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 64,
                'route_name' => 'bank.edit-bank',
                'menu_group_id' => 69,
                'title' => '编辑银行',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 65,
                'route_name' => 'bank.delete-bank',
                'menu_group_id' => 69,
                'title' => '删除银行',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 66,
                'route_name' => 'activity.edit-actype',
                'menu_group_id' => 64,
                'title' => '编辑活动分类',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 70,
                'route_name' => 'fundOperation.users',
                'menu_group_id' => 66,
                'title' => '资金操作列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 71,
                'route_name' => 'fundOperation.add-fund',
                'menu_group_id' => 70,
                'title' => '增加额度',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 72,
                'route_name' => 'menu.allRequireInfos',
                'menu_group_id' => 81,
                'title' => '开发管理菜单操作相关需要的数据',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 73,
                'route_name' => 'menu.changeParent',
                'menu_group_id' => 81,
                'title' => '开发管理拖拽分父级接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 74,
                'route_name' => 'menu.add',
                'menu_group_id' => 81,
                'title' => '开发管理菜单添加接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 75,
                'route_name' => 'fundOperation.admins',
                'menu_group_id' => 70,
                'title' => '资金操作列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 76,
                'route_name' => 'fundOperation.add-fund',
                'menu_group_id' => 70,
                'title' => '给管理员增加额度',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 77,
                'route_name' => 'fundOperation.every-day-fund',
                'menu_group_id' => 70,
                'title' => '设置每日人工充值额度',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 79,
                'route_name' => 'artificialRecharge.users',
                'menu_group_id' => 80,
                'title' => '人工充值列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 80,
                'route_name' => 'artificialRecharge.add',
                'menu_group_id' => 80,
                'title' => '给用户人工充值',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 81,
                'route_name' => 'menu.edit',
                'menu_group_id' => 81,
                'title' => '菜单编辑接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 82,
                'route_name' => 'menu.delete',
                'menu_group_id' => 81,
                'title' => '菜单删除接口',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 83,
                'route_name' => 'RechargeCheck.detail',
                'menu_group_id' => 97,
                'title' => '充值审核列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 84,
                'route_name' => 'RechargeCheck.audit-success',
                'menu_group_id' => 97,
                'title' => '充值审核通过',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 85,
                'route_name' => 'RechargeCheck.audit-failure',
                'menu_group_id' => 97,
                'title' => '充值审核驳回',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 86,
                'route_name' => 'fundOperation.fund-change-log',
                'menu_group_id' => 70,
                'title' => '查看管理员充值额度记录',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 87,
                'route_name' => 'accountChangeType.detail',
                'menu_group_id' => 99,
                'title' => '帐变类型列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 88,
                'route_name' => 'accountChangeType.add',
                'menu_group_id' => 99,
                'title' => '添加帐变类型',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 89,
                'route_name' => 'accountChangeType.edit',
                'menu_group_id' => 99,
                'title' => '编辑帐变类型',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 90,
                'route_name' => 'accountChangeType.delete',
                'menu_group_id' => 99,
                'title' => '删除帐变类型',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 91,
                'route_name' => 'route.detail',
                'menu_group_id' => 100,
                'title' => '路由管理列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 92,
                'route_name' => 'route.add',
                'menu_group_id' => 100,
                'title' => '添加路由',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 93,
                'route_name' => 'route.edit',
                'menu_group_id' => 100,
                'title' => '编辑路由',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 94,
                'route_name' => 'route.delete',
                'menu_group_id' => 100,
                'title' => '删除路由',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}