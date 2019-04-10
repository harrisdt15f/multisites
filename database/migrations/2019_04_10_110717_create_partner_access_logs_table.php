<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerAccessLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_access_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('admin_id')->default(0);
			$table->string('admin_username', 64)->default('');
			$table->string('platform_sign', 16);
			$table->string('route', 64);
			$table->char('ip', 16);
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
		Schema::drop('partner_access_logs');
	}

}
