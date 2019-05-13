<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysNoticeChannelTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_notice_channel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64)->comment('消息分类');
            $table->string('platform_sign', 32)->comment('平台标识');
            $table->string('channel_sign', 32)->default('default')->comment('渠道标识');
            $table->string('channel_type', 32)->default('default')->comment('渠道类型');
            $table->string('api_url', 64)->comment('渠道API路径');
            $table->boolean('status')->default(0)->comment('0 禁用 1 启用');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sys_notice_channel');
    }

}
