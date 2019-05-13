<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSalaryReportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_salary_report', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id');
			$table->string('parent_username', 32);
			$table->string('username', 32);
			$table->integer('amount')->unsigned()->default(0);
			$table->integer('real_amount')->unsigned()->default(0);
			$table->integer('bets')->unsigned()->default(0);
			$table->integer('lose')->unsigned()->default(0);
			$table->decimal('ratio', 5, 1)->default(0.0);
			$table->integer('day')->index();
			$table->boolean('status')->default(0);
			$table->integer('add_time')->default(0);
			$table->integer('send_time')->default(0);
			$table->integer('resend_time')->default(0);
			$table->timestamps();
			$table->index(['sign','user_id']);
			$table->index(['top_id','user_id','day','add_time']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_salary_report');
	}

}
