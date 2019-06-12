<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendSystemLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_system_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('log_uuid', 45)->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('origin', 200)->nullable();
			$table->enum('type', array('log','store','change','delete'));
			$table->enum('result', array('success','neutral','failure'));
			$table->enum('level', array('emergency','alert','critical','error','warning','notice','info','debug'));
			$table->string('token', 100)->nullable();
			$table->string('ip', 45);
			$table->string('ips', 200)->nullable();
			$table->integer('user_id')->nullable();
			$table->string('session', 100)->nullable();
			$table->string('lang', 50)->nullable();
			$table->string('device', 20)->nullable();
			$table->string('os', 20)->nullable();
			$table->string('os_version', 50)->nullable();
			$table->string('browser', 50)->nullable();
			$table->string('bs_version', 50)->nullable();
			$table->boolean('device_type')->nullable();
			$table->string('robot', 50)->nullable();
			$table->string('user_agent', 200)->nullable();
			$table->text('inputs', 65535)->nullable();
			$table->text('route', 65535)->nullable();
			$table->integer('route_id')->unsigned()->nullable();
			$table->integer('admin_id')->nullable();
			$table->string('admin_name', 64)->nullable();
			$table->string('username', 64)->nullable();
			$table->integer('menu_id')->nullable();
			$table->string('menu_label', 64)->nullable();
			$table->text('menu_path', 65535)->nullable();
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
		Schema::drop('frontend_system_logs');
	}

}
