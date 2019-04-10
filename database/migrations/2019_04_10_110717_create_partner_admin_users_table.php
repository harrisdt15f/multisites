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
			$table->string('name', 10);
            $table->string('email')->unique('users_email_unique');
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password');
            $table->rememberToken();
			$table->boolean('is_test')->nullable()->default(0);
			$table->integer('group_id')->nullable()->index('partner_admin_users_group_id_fk_idx');
			$table->integer('status')->unsigned()->nullable()->default(1);
			$table->integer('platform_id')->unsigned()->nullable();
			$table->integer('super_id')->unsigned()->nullable()->index('partner_admin_users_super_id_foreign');
			$table->timestamps();
			$table->index(['platform_id','status'], 'fk_platform_id_status');
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
