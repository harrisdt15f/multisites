<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pid')->default(0);
			$table->string('rid', 64)->default('');
			$table->string('title', 64);
			$table->string('route', 64);
			$table->integer('sort')->default(0);
			$table->string('css_class', 64)->default('');
			$table->boolean('type')->default(1);
			$table->boolean('level')->default(1);
			$table->boolean('status')->default(1);
			$table->integer('admin_id')->default(0);
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
		Schema::drop('admin_menus');
	}

}
