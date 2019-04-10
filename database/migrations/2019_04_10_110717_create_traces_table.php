<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTracesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('traces', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('top_id')->index();
			$table->integer('parent_id')->index();
			$table->string('rid', 256)->default('')->index();
			$table->string('username', 64)->default('');
			$table->string('series_id', 32);
			$table->string('lottery_id', 32);
			$table->string('method_id', 32)->index();
			$table->string('method_name', 32)->index();
			$table->text('bet_number');
			$table->integer('times')->default(1);
			$table->decimal('single_price', 15, 4);
			$table->decimal('total_price', 15, 4);
			$table->boolean('win_stop');
			$table->boolean('mode')->default(1);
			$table->integer('user_prize_group');
			$table->integer('bet_prize_group');
			$table->integer('total_issues');
			$table->integer('finished_issues');
			$table->integer('canceled_issues');
			$table->integer('finished_amount');
			$table->integer('canceled_amount');
			$table->string('start_issue', 16);
			$table->string('now_issue', 16);
			$table->string('end_issue', 16);
			$table->string('stopd_issue', 16);
			$table->text('issue_process', 65535);
			$table->integer('add_time');
			$table->integer('stop_time');
			$table->integer('cancel_time');
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
		Schema::drop('traces');
	}

}
