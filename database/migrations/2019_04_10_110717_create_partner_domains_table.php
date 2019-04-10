<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerDomainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_domains', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('platform_sign');
			$table->string('platform_name');
			$table->boolean('type')->default(1);
			$table->string('domain', 64);
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
		Schema::drop('partner_domains');
	}

}
