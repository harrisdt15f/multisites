<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminAccessLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_access_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('admin_id')->default(0);
			$table->string('admin_username', 64)->default('');
			$table->string('route', 64);
			$table->char('ip', 15);
			$table->text('params', 65535);
			$table->integer('day');
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
		Schema::drop('admin_access_logs');
	}

}
