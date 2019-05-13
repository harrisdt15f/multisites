<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('username', 64)->default('');
			$table->integer('top_id')->index();
			$table->string('rid', 256)->default('');
			$table->integer('parent_id');
			$table->boolean('is_tester')->default(0);
			$table->string('series_id', 32);
            $table->string('lottery_sign', 32);
            $table->string('method_sign', 32);
			$table->string('method_name', 32);
			$table->integer('user_prize_group');
			$table->integer('bet_prize_group');
			$table->integer('trace_id')->default(0);
			$table->boolean('mode');
			$table->integer('times')->unsigned();
            $table->decimal('price', 15, 4);
            $table->decimal('total_cost', 15, 4)->default(0.0000);
			$table->string('issue', 32);
			$table->text('bet_number', 16777215);
            $table->string('open_number', 64)->default('');
			$table->string('prize_set', 64);
			$table->boolean('is_win')->default(0);
			$table->decimal('bonus', 15, 4)->default(0.0000);
			$table->decimal('point', 15, 4)->default(0.0000);
			$table->char('ip', 15);
			$table->char('proxy_ip', 15);
			$table->boolean('bet_from')->default(1);
			$table->boolean('status')->default(0);
			$table->boolean('status_input')->default(0);
			$table->boolean('status_count')->default(0);
			$table->boolean('status_prize')->default(0);
			$table->boolean('status_point')->default(0);
			$table->boolean('status_trace')->default(0);
			$table->boolean('status_stat')->default(0);
			$table->integer('time_bought')->default(0);
			$table->integer('time_input')->default(0);
			$table->integer('time_count')->default(0);
			$table->integer('time_prize')->default(0);
			$table->integer('time_point')->default(0);
			$table->integer('time_trace')->default(0);
			$table->integer('time_cancel')->default(0);
			$table->integer('time_stat')->default(0);
            $table->index(['user_id', 'lottery_sign', 'time_bought']);
            $table->index(['lottery_sign', 'time_bought']);
            $table->index(['issue', 'user_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
