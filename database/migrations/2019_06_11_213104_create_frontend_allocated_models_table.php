<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendAllocatedModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_allocated_models', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('label', 20)->nullable();
			$table->string('en_name', 45)->nullable();
			$table->integer('pid')->nullable();
			$table->boolean('type')->nullable();
			$table->string('value', 128)->nullable();
			$table->boolean('show_num')->nullable();
			$table->boolean('status')->nullable();
			$table->boolean('level')->nullable();
			$table->boolean('is_homepage_display')->nullable();
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
		Schema::drop('frontend_allocated_models');
	}

}
