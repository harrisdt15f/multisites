<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPartnerAdminRouteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('partner_admin_route', function(Blueprint $table)
		{
			$table->foreign('menu_group_id', 'fk_partner_admin_route_menu_group')->references('id')->on('partner_admin_menus')->onUpdate('CASCADE')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('partner_admin_route', function(Blueprint $table)
		{
			$table->dropForeign('fk_partner_admin_route_menu_group');
		});
	}

}
