<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackendAdminRechargehumanLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backend_admin_rechargehuman_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('type')->nullable();
			$table->boolean('in_out')->nullable();
			$table->integer('super_admin_id')->nullable();
			$table->string('super_admin_name', 45)->nullable();
			$table->integer('admin_id')->nullable();
			$table->string('admin_name', 45)->nullable();
			$table->integer('user_id')->nullable();
			$table->string('user_name', 45)->nullable();
			$table->decimal('amount', 10)->nullable();
			$table->text('comment', 65535)->nullable();
			$table->integer('audit_flow_id')->nullable();
			$table->boolean('status')->nullable();
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
		Schema::drop('backend_admin_rechargehuman_logs');
	}

}
