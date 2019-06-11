<?php

use Illuminate\Database\Seeder;

class BackendAdminMessageArticlesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_message_articles')->delete();
        
        \DB::table('backend_admin_message_articles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'category_id' => 5,
                'title' => '啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊',
                'summary' => '千位',
                'content' => '<p>123123123</p>',
                'search_text' => '123',
                'is_for_agent' => 1,
                'status' => 0,
                'audit_flow_id' => 250,
                'add_admin_id' => 23,
                'last_update_admin_id' => 23,
                'sort' => 1,
                'created_at' => '2019-05-11 11:35:09',
                'updated_at' => '2019-05-23 13:47:14',
                'pic_path' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'category_id' => 1,
                'title' => '123',
                'summary' => '123123123123121231',
                'content' => '<p>胜多负少的付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付付多多多多多多多多多多多多多多多多多多多多多多的点点滴滴多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多多咋整咋整做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做做灌灌灌灌灌过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过过</p>',
                'search_text' => '123',
                'is_for_agent' => 0,
                'status' => 0,
                'audit_flow_id' => 251,
                'add_admin_id' => 23,
                'last_update_admin_id' => 23,
                'sort' => 3,
                'created_at' => '2019-05-11 11:40:44',
                'updated_at' => '2019-05-23 13:45:36',
                'pic_path' => NULL,
            ),
            2 => 
            array (
                'id' => 4,
                'category_id' => 4,
                'title' => '123123123',
                'summary' => '12312313',
                'content' => '<p>12312312312</p>',
                'search_text' => '1233123123',
                'is_for_agent' => 1,
                'status' => 0,
                'audit_flow_id' => 253,
                'add_admin_id' => 23,
                'last_update_admin_id' => 23,
                'sort' => 2,
                'created_at' => '2019-05-11 13:52:10',
                'updated_at' => '2019-05-23 13:47:14',
                'pic_path' => NULL,
            ),
        ));
        
        
    }
}