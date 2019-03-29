<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlatformsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('platforms', function(Blueprint $table)
		{
			$table->increments('platform_id');
			$table->string('platform_name', 20);
			$table->string('platform_sign', 20);
			$table->integer('status')->unsigned()->nullable()->default(1);
			$table->text('comments');
			$table->timestamps();
			$table->index(['platform_id','status'], 'ID');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('platforms');
	}

}
