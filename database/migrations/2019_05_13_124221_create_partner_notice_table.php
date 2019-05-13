<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerNoticeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_notice', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('type')->default(1)->comment('类型');
            $table->boolean('area')->default(1)->comment('区域');
            $table->string('title', 64)->comment('标题');
            $table->text('content', 65535)->comment('描述');
            $table->date('start_day')->comment('开始时间');
            $table->date('end_day')->comment('结束时间');
            $table->boolean('top_score')->default(0)->comment('置顶权重');
            $table->boolean('status')->default(0)->comment('0 禁用 1 启用');
            $table->integer('admin_id')->default(0)->comment('管理员id');
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
        Schema::drop('partner_notice');
    }

}
