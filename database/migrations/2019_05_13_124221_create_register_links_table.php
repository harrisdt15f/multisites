<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegisterLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('register_links', function(Blueprint $table)
		{
			$table->increments('id')->comment('ID');
			$table->boolean('is_tester')->nullable()->default(0);
			$table->integer('user_id')->unsigned()->index('idx_user')->comment('用户ID');
			$table->string('username', 32);
			$table->integer('prize_group')->nullable()->comment('奖金组');
			$table->boolean('type')->nullable()->default(0)->comment('链接注册还是扫描注册');
			$table->integer('valid_days')->unsigned()->comment('有效时间 单位天');
			$table->boolean('is_agent')->default(0)->comment('0  用户 1 代理');
			$table->char('keyword', 32)->unique('keyword');
			$table->string('note', 100)->nullable()->comment('链接备注');
			$table->string('channel', 50)->nullable()->comment('推广渠道');
			$table->string('agent_qqs', 50)->nullable();
			$table->integer('created_count')->unsigned()->default(0);
			$table->string('url')->comment('url内容');
			$table->boolean('status')->default(0)->comment('状态(0:正常;1:关闭)');
			$table->dateTime('expired_at')->nullable()->comment('过期时间');
			$table->timestamps();
			$table->index(['user_id','status'], 'idx_search');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('register_links');
	}

}
