<?php

use Illuminate\Database\Seeder;

class FrontendLotteryFnfBetableListsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_lottery_fnf_betable_lists')->delete();
        
        \DB::table('frontend_lottery_fnf_betable_lists')->insert(array (
            0 => 
            array (
                'id' => 3,
                'method_id' => 81,
                'sort' => 1,
                'created_at' => '2019-06-04 15:01:43',
                'updated_at' => '2019-06-05 15:15:23',
            ),
            1 => 
            array (
                'id' => 4,
                'method_id' => 84,
                'sort' => 2,
                'created_at' => '2019-06-04 15:02:27',
                'updated_at' => '2019-06-05 15:15:23',
            ),
            2 => 
            array (
                'id' => 5,
                'method_id' => 187,
                'sort' => 3,
                'created_at' => '2019-06-04 15:48:28',
                'updated_at' => '2019-06-05 15:15:23',
            ),
        ));
        
        
    }
}