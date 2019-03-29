<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerAdminGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_admin_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pid');
			$table->string('rid', 64);
			$table->string('name', 32);
			$table->boolean('level')->default(1);
			$table->string('platform_sign', 16);
			$table->integer('member_count')->default(0);
			$table->text('acl', 65535);
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
		Schema::drop('partner_admin_groups');
	}

}
