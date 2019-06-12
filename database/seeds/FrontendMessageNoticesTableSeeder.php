<?php

use Illuminate\Database\Seeder;

class FrontendMessageNoticesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_message_notices')->delete();
        
        \DB::table('frontend_message_notices')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 1,
                'title' => '测试公告22222',
                'content' => '<p>测试公告测试公告测试公告测试公告2222222</p>',
                'start_time' => '2019-06-04 17:34:50',
                'end_time' => '2019-05-31 11:50:32',
                'sort' => 3,
                'status' => 0,
                'admin_id' => 4,
                'created_at' => '2019-05-25 11:50:50',
                'updated_at' => '2019-06-04 17:34:50',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 1,
                'title' => '测试22223334454454',
                'content' => '<p>阿迪沙发上打舒服的沙发水电费</p>',
                'start_time' => '2019-06-04 17:34:50',
                'end_time' => '2019-05-31 12:06:44',
                'sort' => 1,
                'status' => 1,
                'admin_id' => 4,
                'created_at' => '2019-05-25 12:06:51',
                'updated_at' => '2019-06-04 17:34:50',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 1,
                'title' => '水电费水电费打舒服的沙发电视',
                'content' => '<p>水电费打舒服的沙发水电费都是</p>',
                'start_time' => '2019-06-04 17:34:50',
                'end_time' => '2019-05-25 12:07:10',
                'sort' => 2,
                'status' => 1,
                'admin_id' => 4,
                'created_at' => '2019-05-25 12:07:16',
                'updated_at' => '2019-06-04 17:34:50',
            ),
        ));
        
        
    }
}