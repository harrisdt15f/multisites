<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendUsersPrivacyFlowsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_users_privacy_flows', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('admin_id')->nullable();
			$table->string('admin_name', 64)->nullable();
			$table->integer('user_id')->nullable();
			$table->string('username', 64)->nullable();
			$table->text('comment', 65535)->nullable();
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
		Schema::drop('frontend_users_privacy_flows');
	}

}
