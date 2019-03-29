<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->string('rid', 256);
			$table->string('sign', 32)->index();
			$table->boolean('type')->default(1);
			$table->integer('user_level')->default(1);
			$table->boolean('is_tester')->default(1);
			$table->boolean('frozen_type')->default(1);
			$table->string('username', 64);
			$table->string('nickname', 64);
			$table->string('password', 64);
			$table->string('fund_password', 64)->nullable();
			$table->integer('prize_group');
			$table->integer('levels')->default(0);
			$table->string('theme', 32)->default('default');
			$table->char('register_ip', 15);
			$table->char('last_login_ip', 15)->nullable();
			$table->integer('register_time');
			$table->integer('last_login_time')->nullable();
			$table->string('extend_info', 512)->nullable();
			$table->boolean('status')->default(1);
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
		Schema::drop('users');
	}

}
