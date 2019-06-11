<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendUserInvitedRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_user_invited_records', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('username', 64);
			$table->string('device_type', 16)->default('');
			$table->string('brand', 32)->default('');
			$table->integer('invite_code')->default(0);
			$table->char('ip', 15)->default('')->index('user_invite_records_ip_index');
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
		Schema::drop('frontend_user_invited_records');
	}

}
