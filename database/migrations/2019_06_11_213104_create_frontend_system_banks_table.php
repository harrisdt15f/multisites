<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendSystemBanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_system_banks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 45)->nullable();
			$table->string('code', 45)->nullable();
			$table->boolean('pay_type')->nullable();
			$table->boolean('status')->nullable();
			$table->decimal('min_recharge', 10)->nullable();
			$table->decimal('max_recharge', 10)->nullable();
			$table->decimal('min_withdraw', 10)->nullable();
			$table->decimal('max_withdraw', 10)->nullable();
			$table->string('remarks', 128)->nullable();
			$table->string('allow_user_level', 45)->nullable();
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
		Schema::drop('frontend_system_banks');
	}

}
