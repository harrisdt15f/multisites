<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLotteryTraceListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lottery_trace_lists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->string('rid', 256);
			$table->string('username', 64);
			$table->boolean('is_tester')->default(0);
			$table->string('series_id', 32);
			$table->string('lottery_sign', 32);
			$table->string('method_sign', 32);
			$table->string('method_name', 32);
			$table->string('issue', 32);
			$table->text('bet_number');
			$table->integer('times')->default(1);
			$table->decimal('single_price', 15, 4);
			$table->decimal('total_price', 15, 4);
			$table->boolean('mode')->default(1);
			$table->integer('user_prize_group');
			$table->integer('bet_prize_group');
			$table->char('ip', 15);
			$table->string('proxy_ip', 20);
			$table->boolean('bet_from')->default(1);
			$table->boolean('status')->default(0);
			$table->boolean('finished_status')->default(0);
			$table->date('day')->index('trace_list_day_index');
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
		Schema::drop('lottery_trace_lists');
	}

}
