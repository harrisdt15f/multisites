<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityBetConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_bet_config', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('config_sign', 64);
			$table->integer('c_user_type')->default(4);
			$table->boolean('c_bet_time_type')->default(3);
			$table->boolean('c_recharge_time_type')->default(3);
			$table->boolean('c_lose_time_type')->default(3);
			$table->boolean('c_register_type')->default(1);
			$table->boolean('c_fetch_type')->default(1);
			$table->boolean('c_limit_ip')->default(1);
			$table->boolean('c_fetch_more_level')->default(1);
			$table->integer('c_check_amount')->default(5000);
			$table->text('config', 65535);
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
		Schema::drop('activity_bet_config');
	}

}
