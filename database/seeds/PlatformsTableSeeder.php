<?php

use Illuminate\Database\Seeder;

class PlatformsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('platforms')->delete();
        
        \DB::table('platforms')->insert(array (
            0 => 
            array (
                'platform_id' => 1,
                'platform_name' => 'aa',
                'platform_sign' => 'a',
                'status' => 1,
                'comments' => 'aa',
                'created_at' => '2019-03-29 23:50:58',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'platform_id' => 2,
                'platform_name' => 'bb',
                'platform_sign' => 'b',
                'status' => 1,
                'comments' => 'bb',
                'created_at' => '2019-03-29 23:50:58',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}