<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRechargeLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_recharge_log', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('ip', 15)->default('');
			$table->string('order_id', 64)->default('')->index();
			$table->integer('amount')->unsigned()->default(0);
			$table->integer('user_id')->default(0);
			$table->string('username', 32)->default('');
			$table->text('request_params', 65535);
			$table->text('request_back', 16777215);
			$table->text('content', 65535);
			$table->string('request_reason', 128)->default('');
			$table->integer('request_time')->default(0);
			$table->boolean('request_status')->default(0);
			$table->char('back_ip', 15)->default('');
			$table->string('back_reason', 128)->default('');
			$table->integer('back_time')->default(0);
			$table->boolean('back_status')->default(0);
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
		Schema::drop('user_recharge_log');
	}

}
