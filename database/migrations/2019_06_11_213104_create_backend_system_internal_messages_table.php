<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackendSystemInternalMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backend_system_internal_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('operate_admin_id')->nullable();
			$table->integer('receive_admin_id')->nullable();
			$table->integer('receive_group_id')->nullable();
			$table->integer('message_id')->nullable()->comment('notice_messages表 id');
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
		Schema::drop('backend_system_internal_messages');
	}

}
