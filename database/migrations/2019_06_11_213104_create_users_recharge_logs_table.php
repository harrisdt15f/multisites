<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersRechargeLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_recharge_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('company_order_num', 45)->nullable();
			$table->string('log_num', 45)->nullable();
			$table->decimal('real_amount', 10)->nullable();
			$table->boolean('deposit_mode')->nullable();
			$table->boolean('req_type')->nullable();
			$table->string('req_type_1_params')->nullable();
			$table->string('req_type_2_params')->nullable();
			$table->string('user_recharge_logcol2')->nullable();
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
		Schema::drop('users_recharge_logs');
	}

}
