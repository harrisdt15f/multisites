<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLotteryMethodsWaysLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lottery_methods_ways_levels', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('method_id', 16)->nullable();
			$table->boolean('level')->nullable();
			$table->string('position', 45)->nullable();
			$table->integer('count')->nullable();
			$table->decimal('prize', 10)->nullable();
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
		Schema::drop('lottery_methods_ways_levels');
	}

}
