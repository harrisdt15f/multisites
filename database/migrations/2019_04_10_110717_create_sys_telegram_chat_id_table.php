<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysTelegramChatIdTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_telegram_chat_id', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 64)->comment('标题');
			$table->string('chat_id', 64)->comment('Chat Id');
			$table->boolean('type')->default(1)->comment('类型 1 充值 2 提现');
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
		Schema::drop('sys_telegram_chat_id');
	}

}
