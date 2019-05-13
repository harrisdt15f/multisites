<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIssuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('issues', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('lottery_id', 32);
			$table->string('lottery_name', 32);
			$table->string('issue', 64)->nullable();
			$table->integer('issue_rule_id');
			$table->integer('begin_time');
			$table->integer('end_time');
			$table->integer('official_open_time');
			$table->integer('allow_encode_time')->default(0);
			$table->string('official_code', 64)->nullable();
			$table->boolean('status_encode')->default(0);
			$table->boolean('status_calculated')->default(0);
			$table->boolean('status_prize')->default(0);
			$table->boolean('status_commission')->default(0);
			$table->boolean('status_trace')->default(0);
			$table->integer('encode_time')->default(0);
			$table->integer('calculated_time')->default(0);
			$table->integer('prize_time')->default(0);
			$table->integer('commission_time')->default(0);
			$table->integer('trace_time')->default(0);
			$table->integer('encode_id')->nullable();
			$table->string('encode_username', 64)->nullable();
			$table->integer('day')->default(0);
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
		Schema::drop('issues');
	}

}
