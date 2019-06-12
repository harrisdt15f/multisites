<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendUsersTransferedRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_users_transfered_records', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign', 32);
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id')->index('user_admin_transfer_records_user_id_index');
			$table->string('username', 64);
			$table->string('rid', 256);
			$table->boolean('mode')->default(1);
			$table->boolean('type')->default(1);
			$table->bigInteger('amount')->unsigned();
			$table->integer('admin_id')->default(0);
			$table->string('admin_name', 32)->default('0');
			$table->string('reason', 128)->default('');
			$table->string('process_admin_name', 32)->default('0');
			$table->string('process_reason', 128)->default('');
			$table->integer('add_time');
			$table->integer('process_time')->default(0);
			$table->integer('stat_time')->default(0);
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
		Schema::drop('frontend_users_transfered_records');
	}

}
