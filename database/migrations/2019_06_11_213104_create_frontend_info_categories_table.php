<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendInfoCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_info_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 45)->nullable();
			$table->integer('parent')->nullable();
			$table->string('template', 45)->nullable();
			$table->integer('platform_id')->nullable();
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
		Schema::drop('frontend_info_categories');
	}

}
