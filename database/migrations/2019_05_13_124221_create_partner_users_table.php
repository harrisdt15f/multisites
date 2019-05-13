<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 64);
			$table->string('email', 64);
			$table->integer('group_id');
			$table->string('password', 64);
			$table->string('fund_password', 64);
			$table->string('sign', 8);
			$table->string('platform_name', 64);
			$table->string('db_type', 64)->default('table');
			$table->string('theme', 32)->default('default');
			$table->string('remember_token', 64)->default('');
			$table->char('register_ip', 15);
			$table->char('last_login_ip', 15)->default('');
			$table->integer('last_login_time')->default(0);
			$table->integer('admin_id')->default(0);
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
		Schema::drop('partner_users');
	}

}
