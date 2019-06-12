<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountChangeReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_change_reports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign', 32);
			$table->integer('user_id')->index('account_change_report_user_id_index');
			$table->integer('top_id')->nullable();
			$table->integer('parent_id')->nullable();
			$table->string('rid', 256)->index('account_change_report_rid_index');
			$table->string('username', 32);
			$table->integer('from_id')->default(0);
			$table->integer('from_admin_id')->default(0);
			$table->integer('to_id')->default(0);
			$table->string('type_sign', 32);
			$table->string('type_name', 32)->nullable();
			$table->string('lottery_id', 32)->nullable();
			$table->string('method_id', 32)->nullable();
			$table->integer('project_id')->default(0);
			$table->string('issue', 64)->nullable();
			$table->date('day')->index('account_change_report_day_index');
			$table->string('activity_sign', 32)->nullable()->index('account_change_report_activity_sign_index');
			$table->bigInteger('amount')->unsigned()->default(0);
			$table->bigInteger('before_balance')->unsigned()->default(0);
			$table->bigInteger('balance')->unsigned()->default(0);
			$table->bigInteger('before_frozen_balance')->unsigned()->default(0);
			$table->bigInteger('frozen_balance')->unsigned()->default(0);
			$table->boolean('frozen_type')->default(0);
			$table->boolean('is_tester')->default(0);
			$table->integer('process_time')->default(0);
			$table->string('desc', 256);
			$table->timestamps();
			$table->index(['sign','lottery_id','method_id'], 'account_change_report_sign_lottery_id_method_id_index');
			$table->index(['sign','user_id','process_time'], 'account_change_report_sign_user_id_process_time_index');
			$table->index(['sign','project_id','day'], 'account_change_report_sign_project_id_day_index');
			$table->index(['user_id','type_sign','process_time'], 'account_change_report_user_id_type_sign_process_time_index');
			$table->index(['sign','issue','project_id'], 'account_change_report_sign_issue_project_id_index');
			$table->index(['sign','type_sign','process_time'], 'account_change_report_sign_type_sign_process_time_index');
			$table->index(['sign','process_time'], 'account_change_report_sign_process_time_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_change_reports');
	}

}
