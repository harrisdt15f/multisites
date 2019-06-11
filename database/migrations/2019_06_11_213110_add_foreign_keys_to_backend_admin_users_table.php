<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBackendAdminUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('backend_admin_users', function(Blueprint $table)
		{
			$table->foreign('group_id', 'partner_admin_users_group_id_fk')->references('id')->on('backend_admin_access_groups')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('platform_id', 'partner_admin_users_status_foreign')->references('platform_id')->on('platforms')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('backend_admin_users', function(Blueprint $table)
		{
			$table->dropForeign('partner_admin_users_group_id_fk');
			$table->dropForeign('partner_admin_users_status_foreign');
		});
	}

}
