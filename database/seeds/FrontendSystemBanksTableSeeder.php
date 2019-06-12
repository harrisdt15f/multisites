<?php

use Illuminate\Database\Seeder;

class FrontendSystemBanksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_system_banks')->delete();
        
        \DB::table('frontend_system_banks')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '工商银行',
                'code' => 'ICBC',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => '2019-05-03 16:40:56',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '招商银行',
                'code' => 'CMB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'title' => '建设银行',
                'code' => 'CCB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'title' => '农业银行',
                'code' => 'ABC',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'title' => '中国银行',
                'code' => 'BOC',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'title' => '交通银行',
                'code' => 'BOCOM',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'title' => '民生银行',
                'code' => 'CMBC',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'title' => '中信银行',
                'code' => 'CITIC',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'title' => '浦发银行',
                'code' => 'SPDB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'title' => '邮政储蓄银行',
                'code' => 'PSBC',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'title' => '光大银行',
                'code' => 'CEB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'title' => '平安银行',
                'code' => 'PAB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'title' => '广发银行',
                'code' => 'CGB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'title' => '华夏银行',
                'code' => 'HXB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'title' => '兴业银行',
                'code' => 'CIB',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'title' => '上海银行',
                'code' => 'BOS',
                'pay_type' => 1,
                'status' => 1,
                'min_recharge' => '20.00',
                'max_recharge' => '20000.00',
                'min_withdraw' => '100.00',
                'max_withdraw' => '20000.00',
                'remarks' => '单日充值总额无上限；无手续费',
                'allow_user_level' => '1,2,3,4,5,6,7,8,9,10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}