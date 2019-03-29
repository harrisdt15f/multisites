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
			$table->tinyInteger('is_test')->nullable()->default(0);
			$table->integer('group_id')->nullable();
			$table->tinyInteger('status')->nullable()->default(0);
			$table->integer('platform_id')->nullable()->default(1);
            $table->integer('super_id')->unsigned();
			$table->timestamps();
            $table->foreign('platform_id')->references('platform_id')->on('platforms')
                ->onDelete('cascade');
            $table->foreign('super_id')->references('id')->on('partner_admin_users')
                ->onDelete('cascade');
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
