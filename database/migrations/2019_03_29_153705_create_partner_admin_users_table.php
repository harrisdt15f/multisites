<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerAdminUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_admin_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique('users_email_unique');
            $table->timestamp('email_verified_at')->nullable();
			$table->string('password');
            $table->rememberToken();
			$table->boolean('is_test')->nullable()->default(0);
			$table->integer('group_id')->nullable();
			$table->boolean('status')->nullable()->default(0);
			$table->integer('platform_id')->nullable()->default(1);
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
		Schema::drop('partner_admin_users');
	}

}
