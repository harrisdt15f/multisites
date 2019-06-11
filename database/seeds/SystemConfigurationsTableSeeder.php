<?php

use Illuminate\Database\Seeder;

class SystemConfigurationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('system_configurations')->delete();
        
        \DB::table('system_configurations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'system',
                'name' => '系统相关',
                'description' => '所有系统相关配置都保存此处阿市领导和拉萨都发生了很久开发和拉萨发号施令发 i 很舒服啦和斯洛伐克乱收费发货啦很舒服阿树梨花开罚舒服了哈舒服了哈死了还发了舒服哈说烦阿口角是非看见啊舒服',
                'value' => NULL,
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 14:49:03',
                'updated_at' => '2019-05-21 21:46:17',
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'min_bet_prize_group',
                'name' => '最低投注奖金组',
                'description' => '最低投注奖金组',
                'value' => '1800',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 14:50:34',
                'updated_at' => '2019-05-06 14:50:34',
            ),
            2 => 
            array (
                'id' => 3,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'max_bet_prize_group',
                'name' => '最高投注奖金组',
                'description' => '最高投注奖金组',
                'value' => '1960',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 15:25:13',
                'updated_at' => '2019-05-06 15:25:13',
            ),
            3 => 
            array (
                'id' => 4,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'min_user_prize_group',
                'name' => '最低开户奖金组',
                'description' => '最低开户奖金组',
                'value' => '1800',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 15:25:45',
                'updated_at' => '2019-05-06 15:25:45',
            ),
            4 => 
            array (
                'id' => 5,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'max_user_prize_group',
                'name' => '最高开户奖金组',
                'description' => '最高开户奖金组',
                'value' => '1960',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 15:26:59',
                'updated_at' => '2019-05-06 15:26:59',
            ),
            5 => 
            array (
                'id' => 6,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'max_withdraw',
                'name' => '最大提现次数',
                'description' => '最大提现次数',
                'value' => '5000',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 15:27:25',
                'updated_at' => '2019-05-06 15:27:25',
            ),
            6 => 
            array (
                'id' => 8,
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'admin_fund_control',
                'name' => '管理员金额限制',
                'description' => '管理员金额限制',
                'value' => NULL,
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 15:31:52',
                'updated_at' => '2019-05-06 15:31:52',
            ),
            7 => 
            array (
                'id' => 9,
                'parent_id' => 8,
                'pid' => 1,
                'sign' => 'admin_recharge_daily_limit',
                'name' => '每日管理员充值限制金额',
                'description' => '每日管理员充值限制金额',
                'value' => '80000',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-06 15:32:26',
                'updated_at' => '2019-05-24 11:48:13',
            ),
            8 => 
            array (
                'id' => 10,
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'app_download_urls',
                'name' => 'app下载地址相关',
                'description' => '应用下载地址相关',
                'value' => NULL,
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-16 11:43:39',
                'updated_at' => '2019-05-16 11:47:03',
            ),
            9 => 
            array (
                'id' => 11,
                'parent_id' => 10,
                'pid' => 1,
                'sign' => 'app_download_url',
                'name' => 'app下载地址',
                'description' => '应用下载地址',
                'value' => 'http://www.lottery.com/api/download',
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-16 11:49:01',
                'updated_at' => '2019-05-16 11:49:01',
            ),
            10 => 
            array (
                'id' => 12,
                'parent_id' => 10,
                'pid' => 1,
                'sign' => 'app_version',
                'name' => 'app 版本',
                'description' => '应用版本',
                'value' => '1.0',
                'add_admin_id' => 1,
                'last_update_admin_id' => 1,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-05-16 11:50:13',
                'updated_at' => '2019-05-16 11:50:13',
            ),
            11 => 
            array (
                'id' => 25,
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'issue',
                'name' => '奖期相关',
                'description' => '奖期相关的所有配置',
                'value' => NULL,
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 0,
                'created_at' => '2019-06-03 21:52:54',
                'updated_at' => '2019-06-03 21:52:54',
            ),
            12 => 
            array (
                'id' => 26,
                'parent_id' => 25,
                'pid' => 1,
                'sign' => 'generate_issue_time',
                'name' => '生成奖期时间',
                'description' => '每天自动生成奖期的时间',
                'value' => '15:36',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 0,
                'created_at' => '2019-06-03 21:52:54',
                'updated_at' => '2019-06-04 15:44:13',
            ),
            13 => 
            array (
                'id' => 27,
                'parent_id' => 1,
                'pid' => 1,
                'sign' => 'W_qwewe',
                'name' => 'w_12345',
                'description' => '会贺卡和地方喝酒啊哈 阿时间点饭哈卡积分阿书法家发空间啊好烦等哈发的阿空加后付款阿含经饭哈说付款时间点饭水电费就好水电费合适的返回水电费会计核算地方',
                'value' => '112',
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'status' => 1,
                'display' => 1,
                'created_at' => '2019-06-04 10:14:31',
                'updated_at' => '2019-06-04 10:14:31',
            ),
        ));
        
        
    }
}