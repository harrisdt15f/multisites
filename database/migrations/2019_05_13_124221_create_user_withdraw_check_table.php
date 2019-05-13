<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserWithdrawCheckTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_withdraw_check', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id');
			$table->string('username', 64);
			$table->string('rid', 256)->index();
			$table->integer('withdraw_id')->default(0);
			$table->integer('admin_id');
			$table->string('admin_name', 64);
			$table->boolean('status')->default(0);
			$table->string('reason', 64)->default('');
			$table->integer('checked_time')->default(0);
			$table->timestamps();
			$table->index(['top_id','user_id','status']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_withdraw_check');
	}

}
