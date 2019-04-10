<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerAdminMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_admin_menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('label', 20)->nullable();
			$table->string('en_name', 50)->nullable();
			$table->string('route', 50)->nullable()->default('#');
			$table->integer('pid')->nullable()->default(0);
			$table->string('icon', 50)->nullable();
			$table->text('class', 65535)->nullable();
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
		Schema::drop('partner_admin_menus');
	}

}
