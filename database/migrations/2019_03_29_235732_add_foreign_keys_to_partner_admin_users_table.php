<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPartnerAdminUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('partner_admin_users', function(Blueprint $table)
		{
			$table->foreign('platform_id', 'partner_admin_users_status_foreign')->references('platform_id')->on('platforms')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('super_id')->references('id')->on('partner_admin_users')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('partner_admin_users', function(Blueprint $table)
		{
			$table->dropForeign('partner_admin_users_status_foreign');
			$table->dropForeign('partner_admin_users_super_id_foreign');
		});
	}

}
