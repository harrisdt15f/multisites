<?php

use Illuminate\Database\Seeder;

class FrontendActivityContentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_activity_contents')->delete();
        
        \DB::table('frontend_activity_contents')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '测试',
                'content' => '1',
                'pic_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/08ede871294a83ab7b84cc6d03d37d2a.png',
                'icon_path' => NULL,
                'start_time' => NULL,
                'end_time' => NULL,
                'status' => 1,
                'admin_id' => 4,
                'admin_name' => 'york',
                'redirect_url' => '1111',
                'is_time_interval' => 0,
                'thumbnail_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/sm_08ede871294a83ab7b84cc6d03d37d2a.png',
                'sort' => 1,
                'created_at' => '2019-05-17 15:15:38',
                'updated_at' => '2019-05-17 15:22:12',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '温泉恶趣味',
                'content' => '11111',
                'pic_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/27e8b885309379b2ed877f78b33230eb.jpg',
                'icon_path' => NULL,
                'start_time' => '2019-05-02 12:01:12',
                'end_time' => '2019-05-25 12:01:16',
                'status' => 1,
                'admin_id' => 4,
                'admin_name' => 'york',
                'redirect_url' => '1111',
                'is_time_interval' => 1,
                'thumbnail_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/sm_27e8b885309379b2ed877f78b33230eb.jpg',
                'sort' => 2,
                'created_at' => '2019-05-23 12:01:27',
                'updated_at' => '2019-05-23 12:01:27',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => '请问请问',
                'content' => '22222玩儿玩儿玩儿玩玩儿玩儿玩儿玩儿自行车自学成才新泽西州重新注册儿温热温热温热温热温热温柔',
                'pic_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/6058c027a6daa9febf819ea81da291f8.jpg',
                'icon_path' => NULL,
                'start_time' => '2019-05-22 12:01:51',
                'end_time' => '2019-05-31 12:01:55',
                'status' => 1,
                'admin_id' => 4,
                'admin_name' => 'york',
                'redirect_url' => '1111玩儿温热温柔温热温柔玩儿温热我分为玩儿玩儿',
                'is_time_interval' => 1,
                'thumbnail_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/sm_6058c027a6daa9febf819ea81da291f8.jpg',
                'sort' => 3,
                'created_at' => '2019-05-23 12:02:08',
                'updated_at' => '2019-05-23 16:01:40',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'wqeqweqwe',
                'content' => 'qweqweqwe',
                'pic_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/b50214853acdaf3a0dd71a44484d2346.jpg',
                'icon_path' => NULL,
                'start_time' => NULL,
                'end_time' => NULL,
                'status' => 1,
                'admin_id' => 4,
                'admin_name' => 'york',
                'redirect_url' => 'qweqe',
                'is_time_interval' => 0,
                'thumbnail_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/sm_b50214853acdaf3a0dd71a44484d2346.jpg',
                'sort' => 4,
                'created_at' => '2019-06-05 16:21:26',
                'updated_at' => '2019-06-05 16:21:26',
            ),
        ));
        
        
    }
}