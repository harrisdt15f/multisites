<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendSystemAdsTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_system_ads_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 32)->nullable();
			$table->boolean('type')->nullable();
			$table->boolean('status')->nullable();
			$table->timestamps();
			$table->boolean('ext_type')->nullable();
			$table->integer('l_size')->nullable();
			$table->integer('w_size')->nullable();
			$table->integer('size')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('frontend_system_ads_types');
	}

}
