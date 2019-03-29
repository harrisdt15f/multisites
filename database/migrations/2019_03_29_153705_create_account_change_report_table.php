<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountChangeReportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_change_report', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign', 32);
			$table->integer('user_id')->index();
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->string('rid', 256)->index();
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
			$table->date('day')->index();
			$table->string('activity_sign', 32)->nullable()->index();
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
			$table->index(['sign','issue','project_id']);
			$table->index(['sign','type_sign','process_time']);
			$table->index(['sign','process_time']);
			$table->index(['user_id','type_sign','process_time']);
			$table->index(['sign','lottery_id','method_id']);
			$table->index(['sign','user_id','process_time']);
			$table->index(['sign','project_id','day']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_change_report');
	}

}
