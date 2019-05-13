<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityBetLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_bet_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('activity_id');
			$table->integer('config_id');
			$table->integer('user_id');
			$table->integer('username');
			$table->integer('day');
			$table->integer('level');
			$table->integer('bonus');
			$table->integer('fetched_time');
			$table->integer('current_bets')->default(0);
			$table->integer('current_recharge')->default(0);
			$table->integer('current_lose')->default(0);
			$table->char('ip', 15);
			$table->boolean('status')->default(1);
			$table->integer('checked_admin_id');
			$table->integer('checked_time');
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
		Schema::drop('activity_bet_logs');
	}

}
