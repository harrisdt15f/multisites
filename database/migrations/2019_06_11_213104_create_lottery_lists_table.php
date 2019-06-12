<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLotteryListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lottery_lists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('cn_name', 32);
			$table->string('en_name', 32);
			$table->string('series_id', 32);
			$table->boolean('is_fast')->default(1);
			$table->boolean('auto_open')->default(0);
			$table->integer('max_trace_number')->default(50);
			$table->integer('day_issue');
			$table->string('issue_format', 32);
			$table->string('issue_type', 32)->default('day');
			$table->string('valid_code', 256);
			$table->integer('code_length');
			$table->string('positions', 256);
			$table->integer('min_prize_group');
			$table->integer('max_prize_group');
			$table->integer('min_times');
			$table->integer('max_times');
			$table->string('valid_modes', 32);
			$table->boolean('status')->default(0);
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
		Schema::drop('lottery_lists');
	}

}
