<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTraceListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trace_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign', 32);
			$table->integer('user_id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->string('rid', 256);
			$table->string('username', 64);
			$table->string('series_id', 32);
			$table->string('lottery_id', 32);
			$table->string('method_id', 32);
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
			$table->char('proxy_ip', 15);
			$table->boolean('bet_from')->default(1);
			$table->boolean('status')->default(0);
			$table->boolean('finished_status')->default(0);
			$table->date('day')->index();
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
		Schema::drop('trace_list');
	}

}
