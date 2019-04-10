<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPartnerAccessGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('partner_access_group', function(Blueprint $table)
		{
			$table->foreign('platform_id', 'fk_partner_access_platform_id')->references('platform_id')->on('platforms')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('partner_access_group', function(Blueprint $table)
		{
			$table->dropForeign('fk_partner_access_platform_id');
		});
	}

}
