<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackendAdminRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backend_admin_routes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('route_name', 64)->nullable();
			$table->string('controller', 64)->nullable();
			$table->string('method', 64)->nullable();
			$table->integer('menu_group_id')->unsigned()->nullable()->index('fk_partner_admin_route_menu_group_idx')->comment('菜单组id');
			$table->string('title', 45)->nullable();
			$table->text('description', 65535)->nullable();
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
		Schema::drop('backend_admin_routes');
	}

}
