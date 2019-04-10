<?php

use Illuminate\Database\Seeder;

class RegisterLinksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('register_links')->delete();
        
        \DB::table('register_links')->insert(array (
            0 => 
            array (
                'id' => 1,
                'is_tester' => 0,
                'user_id' => 2,
                'username' => 'test888',
                'prize_group' => 1956,
                'type' => 0,
                'valid_days' => 0,
                'is_agent' => 1,
                'keyword' => '0db310ce',
                'note' => NULL,
                'channel' => '',
                'agent_qqs' => NULL,
                'created_count' => 0,
                'url' => 'https://chunqiucp1.com/reg/0db310ce',
                'status' => 0,
                'expired_at' => NULL,
                'created_at' => '2018-07-05 10:37:46',
                'updated_at' => '2018-07-05 14:30:45',
            ),
        ));
        
        
    }
}