<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackendSystemMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backend_system_menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('label', 20)->nullable();
			$table->string('en_name', 50)->nullable();
			$table->string('route', 50)->nullable()->default('#');
			$table->integer('pid')->nullable()->default(0)->comment('菜单的父级别');
			$table->string('icon', 50)->nullable();
			$table->boolean('display')->nullable()->default(1);
			$table->integer('level')->nullable()->default(1);
			$table->integer('sort')->nullable();
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
		Schema::drop('backend_system_menus');
	}

}
