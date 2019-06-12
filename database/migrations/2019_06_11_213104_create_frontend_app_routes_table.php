<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendAppRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_app_routes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('route_name', 64)->nullable();
			$table->string('controller', 64)->nullable();
			$table->string('method', 64)->nullable();
			$table->integer('frontend_model_id')->nullable();
			$table->string('title', 45)->nullable();
			$table->text('description', 65535)->nullable();
			$table->boolean('is_open')->nullable()->default(0);
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
		Schema::drop('frontend_app_routes');
	}

}
