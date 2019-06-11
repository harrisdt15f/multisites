<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBackendAdminRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('backend_admin_routes', function(Blueprint $table)
		{
			$table->foreign('menu_group_id', 'fk_partner_admin_route_menu_group')->references('id')->on('backend_system_menus')->onUpdate('CASCADE')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('backend_admin_routes', function(Blueprint $table)
		{
			$table->dropForeign('fk_partner_admin_route_menu_group');
		});
	}

}
