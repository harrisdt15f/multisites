<?php

use Illuminate\Database\Seeder;

class FrontendAllocatedModelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_allocated_models')->delete();
        
        \DB::table('frontend_allocated_models')->insert(array (
            0 => 
            array (
                'id' => 8,
                'label' => '首页',
                'en_name' => 'homepage',
                'pid' => 0,
                'type' => 1,
                'value' => NULL,
                'show_num' => NULL,
                'status' => 1,
                'level' => 1,
                'is_homepage_display' => NULL,
                'updated_at' => '2019-06-03 14:21:05',
                'created_at' => '2019-06-03 14:21:05',
            ),
            1 => 
            array (
                'id' => 9,
                'label' => '一导航',
                'en_name' => 'nav.one',
                'pid' => 8,
                'type' => 1,
                'value' => NULL,
                'show_num' => NULL,
                'status' => 1,
                'level' => 2,
                'is_homepage_display' => NULL,
                'updated_at' => '2019-06-07 15:36:25',
                'created_at' => '2019-06-03 14:23:16',
            ),
            2 => 
            array (
                'id' => 10,
                'label' => '图标',
                'en_name' => 'logo',
                'pid' => 9,
                'type' => 1,
                'value' => '/uploaded_files/aa_1/logo_aa_1/b16d6f0889bcdb34e20cb0f3634cee3e.png',
                'show_num' => NULL,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-03 14:24:11',
                'created_at' => '2019-06-03 14:24:11',
            ),
            3 => 
            array (
                'id' => 11,
                'label' => '联系客服',
                'en_name' => 'customer.service',
                'pid' => 9,
                'type' => 1,
                'value' => 'http://22ssadsd.com',
                'show_num' => NULL,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-03 14:24:39',
                'created_at' => '2019-06-03 14:24:39',
            ),
            4 => 
            array (
                'id' => 12,
                'label' => '网站头ico',
                'en_name' => 'frontend.ico',
                'pid' => 9,
                'type' => 1,
                'value' => '/uploaded_files/aa_1/frontend_aa_1/ico/40ca23f4e87bdf6c5f9620c00b330845.ico',
                'show_num' => NULL,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-07 15:40:53',
                'created_at' => '2019-06-03 14:25:00',
            ),
            5 => 
            array (
                'id' => 13,
                'label' => '轮播图',
                'en_name' => 'banner',
                'pid' => 8,
                'type' => 1,
                'value' => NULL,
                'show_num' => NULL,
                'status' => 1,
                'level' => 2,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-03 14:25:40',
                'created_at' => '2019-06-03 14:25:40',
            ),
            6 => 
            array (
                'id' => 14,
                'label' => '主题板块',
                'en_name' => 'page.model',
                'pid' => 8,
                'type' => 1,
                'value' => NULL,
                'show_num' => NULL,
                'status' => 1,
                'level' => 2,
                'is_homepage_display' => NULL,
                'updated_at' => '2019-06-03 14:26:08',
                'created_at' => '2019-06-03 14:26:08',
            ),
            7 => 
            array (
                'id' => 15,
                'label' => '中奖排行',
                'en_name' => 'winning.ranking',
                'pid' => 14,
                'type' => 1,
                'value' => NULL,
                'show_num' => 9,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-07 15:37:13',
                'created_at' => '2019-06-03 14:26:32',
            ),
            8 => 
            array (
                'id' => 16,
                'label' => '二维码',
                'en_name' => 'qr.code',
                'pid' => 14,
                'type' => 1,
                'value' => '/uploaded_files/aa_1/qr.code_aa_1/c999a4d36517f529f9f63733e86c2556.png',
                'show_num' => NULL,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-07 15:39:22',
                'created_at' => '2019-06-03 14:26:52',
            ),
            9 => 
            array (
                'id' => 17,
                'label' => '公告',
                'en_name' => 'notice',
                'pid' => 14,
                'type' => 1,
                'value' => NULL,
                'show_num' => 5,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-03 14:27:13',
                'created_at' => '2019-06-03 14:27:13',
            ),
            10 => 
            array (
                'id' => 18,
                'label' => '热门彩种一',
                'en_name' => 'popularLotteries.one',
                'pid' => 14,
                'type' => 1,
                'value' => NULL,
                'show_num' => 4,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-03 14:27:39',
                'created_at' => '2019-06-03 14:27:39',
            ),
            11 => 
            array (
                'id' => 19,
                'label' => '热门彩种二',
                'en_name' => 'popularLotteries.two',
                'pid' => 14,
                'type' => 1,
                'value' => NULL,
                'show_num' => 3,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-03 14:27:59',
                'created_at' => '2019-06-03 14:27:59',
            ),
            12 => 
            array (
                'id' => 20,
                'label' => '热门活动',
                'en_name' => 'activity',
                'pid' => 14,
                'type' => 1,
                'value' => NULL,
                'show_num' => 4,
                'status' => 1,
                'level' => 3,
                'is_homepage_display' => 1,
                'updated_at' => '2019-06-03 14:28:20',
                'created_at' => '2019-06-03 14:28:20',
            ),
            13 => 
            array (
                'id' => 21,
                'label' => '登录模块',
                'en_name' => 'login-model',
                'pid' => 0,
                'type' => 1,
                'value' => NULL,
                'show_num' => NULL,
                'status' => 1,
                'level' => 1,
                'is_homepage_display' => NULL,
                'updated_at' => '2019-06-04 15:44:16',
                'created_at' => '2019-06-04 15:44:16',
            ),
            14 => 
            array (
                'id' => 22,
                'label' => '登录',
                'en_name' => 'login',
                'pid' => 21,
                'type' => 1,
                'value' => NULL,
                'show_num' => NULL,
                'status' => 1,
                'level' => 2,
                'is_homepage_display' => NULL,
                'updated_at' => '2019-06-04 15:45:55',
                'created_at' => '2019-06-04 15:45:55',
            ),
            15 => 
            array (
                'id' => 23,
                'label' => '注册',
                'en_name' => 'register',
                'pid' => 21,
                'type' => 1,
                'value' => NULL,
                'show_num' => NULL,
                'status' => 1,
                'level' => 2,
                'is_homepage_display' => NULL,
                'updated_at' => '2019-06-04 15:46:18',
                'created_at' => '2019-06-04 15:46:18',
            ),
        ));
        
        
    }
}