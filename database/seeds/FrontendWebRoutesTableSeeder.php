<?php

use Illuminate\Database\Seeder;

class FrontendWebRoutesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_web_routes')->delete();
        
        \DB::table('frontend_web_routes')->insert(array (
            0 => 
            array (
                'id' => 18,
                'route_name' => 'web-api.HomepageController.logo',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\Homepage\\HomepageController',
                'method' => 'logo',
                'frontend_model_id' => 10,
                'title' => '首页logo',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:39:26',
                'updated_at' => '2019-06-05 20:43:19',
            ),
            1 => 
            array (
                'id' => 19,
                'route_name' => 'web-api.HomepageController.ico',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\Homepage\\HomepageController',
                'method' => 'ico',
                'frontend_model_id' => 12,
                'title' => '网站头ico',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:40:13',
                'updated_at' => '2019-06-05 20:43:20',
            ),
            2 => 
            array (
                'id' => 20,
                'route_name' => 'web-api.HomepageController.banner',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\Homepage\\HomepageController',
                'method' => 'banner',
                'frontend_model_id' => 13,
                'title' => '首页轮播图',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:40:30',
                'updated_at' => '2019-06-05 20:43:20',
            ),
            3 => 
            array (
                'id' => 21,
                'route_name' => 'web-api.HomepageController.qrcode',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\Homepage\\HomepageController',
                'method' => 'qrCode',
                'frontend_model_id' => 16,
                'title' => '首页二维码',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:40:47',
                'updated_at' => '2019-06-05 20:43:24',
            ),
            4 => 
            array (
                'id' => 22,
                'route_name' => 'web-api.HomepageController.notice',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\Homepage\\HomepageController',
                'method' => 'notice',
                'frontend_model_id' => 17,
                'title' => '首页公告列表',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:41:08',
                'updated_at' => '2019-06-05 20:43:26',
            ),
            5 => 
            array (
                'id' => 23,
                'route_name' => 'web-api.HomepageController.popular-lotteries-one',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\Homepage\\HomepageController',
                'method' => 'popularLotteriesOne',
                'frontend_model_id' => 18,
                'title' => '首页热门彩票',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:41:50',
                'updated_at' => '2019-06-05 20:43:26',
            ),
            6 => 
            array (
                'id' => 24,
                'route_name' => 'web-api.HomepageController.activity',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\Homepage\\HomepageController',
                'method' => 'activity',
                'frontend_model_id' => 20,
                'title' => '首页热门活动',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:42:11',
                'updated_at' => '2019-06-05 20:43:33',
            ),
            7 => 
            array (
                'id' => 25,
                'route_name' => 'web-api.login',
                'controller' => 'App\\Http\\Controllers\\FrontendApi\\FrontendAuthController',
                'method' => 'login',
                'frontend_model_id' => 22,
                'title' => '登录',
                'description' => NULL,
                'is_open' => 1,
                'created_at' => '2019-06-04 15:46:53',
                'updated_at' => '2019-06-05 20:43:34',
            ),
        ));
        
        
    }
}