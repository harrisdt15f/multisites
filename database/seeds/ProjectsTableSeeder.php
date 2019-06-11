<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('projects')->delete();
        
        \DB::table('projects')->insert(array (
            0 => 
            array (
                'id' => 10,
                'user_id' => 1,
                'username' => 'harriszhongdai',
                'top_id' => 0,
                'rid' => '1',
                'parent_id' => 0,
                'is_tester' => 0,
                'series_id' => 'ssc',
                'lottery_sign' => 'cqssc',
                'method_sign' => 'ZX5',
                'method_name' => '直选复式',
                'user_prize_group' => 1980,
                'bet_prize_group' => 1860,
                'trace_id' => 0,
                'mode' => 1,
                'times' => 2,
                'price' => '2.0000',
                'total_cost' => '4.0000',
                'issue' => '190601043',
                'bet_number' => '0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9',
                'open_number' => '',
                'prize_set' => '',
                'is_win' => 0,
                'bonus' => '0.0000',
                'point' => '0.0000',
                'ip' => '172.19.0.1',
                'proxy_ip' => '"172.19.0.1"',
                'bet_from' => 1,
                'status' => 0,
                'status_input' => 0,
                'status_count' => 0,
                'status_prize' => 0,
                'status_point' => 0,
                'status_trace' => 0,
                'status_stat' => 0,
                'time_bought' => 1559384003,
                'time_input' => 0,
                'time_count' => 0,
                'time_prize' => 0,
                'time_point' => 0,
                'time_trace' => 0,
                'time_cancel' => 0,
                'time_stat' => 0,
            ),
            1 => 
            array (
                'id' => 11,
                'user_id' => 1,
                'username' => 'harriszhongdai',
                'top_id' => 0,
                'rid' => '1',
                'parent_id' => 0,
                'is_tester' => 0,
                'series_id' => 'ssc',
                'lottery_sign' => 'cqssc',
                'method_sign' => 'ZX5',
                'method_name' => '直选复式',
                'user_prize_group' => 1980,
                'bet_prize_group' => 1860,
                'trace_id' => 0,
                'mode' => 1,
                'times' => 2,
                'price' => '2.0000',
                'total_cost' => '4.0000',
                'issue' => '190605033',
                'bet_number' => '0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9',
                'open_number' => '',
                'prize_set' => '',
                'is_win' => 0,
                'bonus' => '0.0000',
                'point' => '0.0000',
                'ip' => '172.19.0.1',
                'proxy_ip' => '"172.19.0.1"',
                'bet_from' => 1,
                'status' => 0,
                'status_input' => 0,
                'status_count' => 0,
                'status_prize' => 0,
                'status_point' => 0,
                'status_trace' => 0,
                'status_stat' => 0,
                'time_bought' => 1559717401,
                'time_input' => 0,
                'time_count' => 0,
                'time_prize' => 0,
                'time_point' => 0,
                'time_trace' => 0,
                'time_cancel' => 0,
                'time_stat' => 0,
            ),
        ));
        
        
    }
}