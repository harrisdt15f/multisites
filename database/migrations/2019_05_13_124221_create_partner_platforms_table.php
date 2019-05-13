<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerPlatformsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_platforms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign', 16);
			$table->string('platform_name', 64);
			$table->string('db_sign', 32)->default('default');
            $table->string('db_name', 32);
			$table->string('theme', 32)->default('default');
			$table->integer('prize_group_min')->default(1700);
			$table->integer('prize_group_max')->default(1980);
			$table->integer('single_price')->default(2);
			$table->string('open_mode')->default('1|0.1|0.01');
			$table->integer('admin_id')->default(0);
			$table->integer('last_admin_id')->default(0);
			$table->boolean('status')->default(1);
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
		Schema::drop('partner_platforms');
	}

}
