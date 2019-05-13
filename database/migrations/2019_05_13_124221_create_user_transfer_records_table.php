<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTransferRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_transfer_records', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('from_parent_id');
			$table->integer('from_user_id');
			$table->string('from_username', 64);
			$table->integer('to_parent_id');
			$table->integer('to_user_id');
			$table->string('to_username', 64);
			$table->integer('amount')->unsigned();
			$table->integer('add_time');
			$table->integer('day');
			$table->timestamps();
			$table->index(['from_user_id','add_time']);
			$table->index(['to_user_id','add_time']);
            $table->index(['from_user_id', 'to_user_id', 'add_time']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_transfer_records');
	}

}
