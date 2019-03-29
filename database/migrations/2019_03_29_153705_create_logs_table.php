<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('description', 65535)->nullable();
			$table->string('origin', 200)->nullable();
			$table->enum('type', array('log','store','change','delete'));
			$table->enum('result', array('success','neutral','failure'));
			$table->enum('level', array('emergency','alert','critical','error','warning','notice','info','debug'));
			$table->string('token', 100)->nullable();
            $table->ipAddress('ip');
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
		Schema::drop('logs');
	}

}
