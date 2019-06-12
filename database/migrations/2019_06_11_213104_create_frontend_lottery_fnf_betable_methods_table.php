<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendLotteryFnfBetableMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_lottery_fnf_betable_methods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('series_id', 32);
			$table->string('lottery_name', 32);
			$table->string('lottery_id', 32);
			$table->string('method_id', 32);
			$table->string('method_name', 32);
			$table->string('method_group', 32);
			$table->string('method_row', 32)->nullable();
			$table->integer('group_sort')->default(0);
			$table->integer('row_sort')->default(0);
			$table->integer('method_sort')->default(0);
			$table->boolean('show')->default(1);
			$table->boolean('status')->default(0);
			$table->integer('total')->nullable();
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
		Schema::drop('frontend_lottery_fnf_betable_methods');
	}

}
