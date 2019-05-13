<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 128);
			$table->string('sign', 32)->default('');
			$table->string('class_name', 64);
			$table->string('image_name', 128)->default('');
			$table->integer('min_recharge')->default(0);
			$table->integer('min_bet')->default(0);
			$table->boolean('new_user')->default(0);
            $table->integer('start_time')->default(0);
            $table->integer('end_time')->default(0);
			$table->boolean('status')->default(0);
            $table->string('config_sign', 32)->default('');
            $table->string('description', 128)->default('');
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
		Schema::drop('activity_list');
	}

}
