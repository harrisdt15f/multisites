<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRechargeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_recharge', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id');
			$table->string('username', 64);
			$table->string('rid', 128);
			$table->string('order_id', 64)->default('')->index();
			$table->string('channel', 64)->default('');
			$table->string('bank_sign', 32)->default('');
			$table->bigInteger('amount')->unsigned()->default(0);
			$table->bigInteger('real_amount')->unsigned()->default(0);
			$table->string('sign', 32)->default('');
			$table->char('client_ip', 15)->default('');
			$table->string('source', 32)->default('web');
			$table->boolean('status')->default(0);
			$table->string('fail_reason', 256)->default('');
			$table->integer('init_time')->default(0);
			$table->integer('request_time')->default(0);
			$table->integer('callback_time')->default(0);
			$table->integer('stat_time')->default(0);
			$table->string('desc', 256)->default('');
			$table->integer('admin_id')->default(0);
			$table->timestamps();
			$table->index(['user_id','request_time']);
			$table->index(['user_id','callback_time']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_recharge');
	}

}
