<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersSalaryReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_salary_reports', function(Blueprint $table)
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
			$table->integer('day')->index('user_salary_report_day_index');
			$table->boolean('status')->default(0);
			$table->integer('add_time')->default(0);
			$table->integer('send_time')->default(0);
			$table->integer('resend_time')->default(0);
			$table->timestamps();
			$table->index(['top_id','user_id','day','add_time'], 'user_salary_report_top_id_user_id_day_add_time_index');
			$table->index(['sign','user_id'], 'user_salary_report_sign_user_id_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_salary_reports');
	}

}
