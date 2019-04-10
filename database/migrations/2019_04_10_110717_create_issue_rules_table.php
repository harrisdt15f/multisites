<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIssueRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('issue_rules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('lottery_id');
			$table->string('lottery_name');
			$table->time('begin_time');
			$table->time('end_time');
			$table->integer('issue_seconds');
			$table->time('first_time');
			$table->smallInteger('adjust_time');
			$table->smallInteger('encode_time');
			$table->smallInteger('issue_count');
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
		Schema::drop('issue_rules');
	}

}
