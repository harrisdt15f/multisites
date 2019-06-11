<?php

use Illuminate\Database\Seeder;

class FrontendPageBannersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_page_banners')->delete();
        
        \DB::table('frontend_page_banners')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '轮播1',
                'content' => '1',
                'pic_path' => '/uploaded_files/aa_1/Homepagec_Rotation_chart_aa_1/8e7942752dac33da91a9e6ecf49b402e.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/Homepagec_Rotation_chart_aa_1/sm_8e7942752dac33da91a9e6ecf49b402e.jpg',
                'type' => 1,
                'redirect_url' => '111',
                'activity_id' => NULL,
                'status' => 1,
                'start_time' => '2019-05-08 11:04:02',
                'end_time' => '2019-05-22 11:04:05',
                'sort' => 1,
                'created_at' => '2019-05-22 11:04:13',
                'updated_at' => '2019-05-24 18:09:25',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '测试2',
                'content' => '22222',
                'pic_path' => '/uploaded_files/aa_1/Homepagec_Rotation_chart_aa_1/56aa120399361f85a58b7083c92d2e6d.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/Homepagec_Rotation_chart_aa_1/sm_56aa120399361f85a58b7083c92d2e6d.jpg',
                'type' => 1,
                'redirect_url' => '1111',
                'activity_id' => NULL,
                'status' => 0,
                'start_time' => '2019-05-23 11:37:24',
                'end_time' => '2019-05-31 11:37:27',
                'sort' => 2,
                'created_at' => '2019-05-22 11:38:12',
                'updated_at' => '2019-05-24 18:09:25',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => '温泉恶趣味',
                'content' => '11111',
                'pic_path' => '/uploaded_files/aa_1/Homepagec_Rotation_chart_aa_1/a8131b0b97f9879944200b456d5bac2a.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/Homepagec_Rotation_chart_aa_1/sm_a8131b0b97f9879944200b456d5bac2a.jpg',
                'type' => 1,
                'redirect_url' => '1111',
                'activity_id' => NULL,
                'status' => 1,
                'start_time' => '2019-05-23 11:43:37',
                'end_time' => '2019-05-31 11:43:40',
                'sort' => 3,
                'created_at' => '2019-05-22 11:44:41',
                'updated_at' => '2019-05-22 11:44:41',
            ),
        ));
        
        
    }
}