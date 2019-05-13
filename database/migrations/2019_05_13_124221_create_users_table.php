<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 64);
            $table->integer('top_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('rid', 256)->nullable();
            $table->string('sign', 32)->comment('所属平台标识!');
            $table->integer('account_id')->nullable();
            $table->boolean('type')->default(1)->comment('用户类型你:1 直属  2 代理 3 会员');
            $table->integer('vip_level')->nullable()->default(0)->comment('vip等级');
            $table->boolean('is_tester')->nullable()->default(0);
            $table->boolean('frozen_type')->nullable()->default(0)->comment('冻结类型:1, 禁止登录, 2, 禁止投注 3, 禁止提现,4禁止资金操作,5禁止投注');
            $table->string('nickname', 64);
            $table->string('password', 64);
            $table->string('fund_password', 64)->nullable();
            $table->integer('prize_group');
            $table->string('remember_token', 100)->nullable();
            $table->integer('level_deep')->nullable()->default(0)->comment('用户等级深度');
            $table->char('register_ip', 15);
            $table->char('last_login_ip', 15)->nullable();
            $table->integer('register_time')->nullable();
            $table->integer('last_login_time')->nullable();
            $table->string('extend_info', 512)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->index(['sign', 'username']);
            $table->index(['sign', 'parent_id']);
            $table->index(['sign', 'rid']);
            $table->index(['sign', 'vip_level']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
