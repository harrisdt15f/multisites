<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlatformsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('platforms', function(Blueprint $table)
		{
			$table->increments('platform_id');
			$table->string('platform_name', 20);
			$table->string('platform_sign', 20);
			$table->integer('status')->unsigned()->nullable()->default(1);
			$table->text('comments');
            $table->integer('prize_group_min')->nullable()->default(1700);
            $table->integer('prize_group_max')->nullable()->default(1980);
            $table->integer('single_price')->nullable()->default(2);
            $table->string('open_mode')->nullable()->default('1|0.1|0.01');
            $table->integer('admin_id')->nullable();
            $table->integer('last_admin_id')->nullable();
			$table->timestamps();
			$table->index(['platform_id','status'], 'ID');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('platforms');
	}

}
