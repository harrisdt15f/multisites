<?php

use Illuminate\Database\Seeder;

class FrontendLotteryRedirectBetListsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_lottery_redirect_bet_lists')->delete();
        
        \DB::table('frontend_lottery_redirect_bet_lists')->insert(array (
            0 => 
            array (
                'id' => 1,
                'lotteries_id' => '1',
                'pic_path' => '/uploaded_files/aa_1/popular_lotteries_aa_1/08b7983ae39018d77afac9e88b2dcad3.jpg',
                'sort' => 3,
                'created_at' => '2019-05-24 21:13:09',
                'updated_at' => '2019-05-24 21:50:24',
            ),
            1 => 
            array (
                'id' => 2,
                'lotteries_id' => '2',
                'pic_path' => '/uploaded_files/aa_1/popular_lotteries_aa_1/16970942046d1a110f96cfeba2a57271.jpg',
                'sort' => 2,
                'created_at' => '2019-05-24 21:30:48',
                'updated_at' => '2019-06-05 15:10:45',
            ),
            2 => 
            array (
                'id' => 3,
                'lotteries_id' => '7',
                'pic_path' => '/uploaded_files/aa_1/popular_lotteries_aa_1/f3f8e4269124ab7812e54233616152cf.jpg',
                'sort' => 1,
                'created_at' => '2019-05-24 21:31:10',
                'updated_at' => '2019-06-05 15:10:45',
            ),
        ));
        
        
    }
}