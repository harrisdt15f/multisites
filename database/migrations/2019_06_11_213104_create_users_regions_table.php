<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersRegionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_regions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('region_id', 16)->nullable();
			$table->string('region_parent_id', 16)->nullable();
			$table->string('region_name', 64)->nullable();
			$table->boolean('region_level')->nullable()->comment('1.省 2.市(市辖区)3.县(区、市)4.镇(街道)');
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
		Schema::drop('users_regions');
	}

}
