<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserStatDayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_stat_day', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->string('rid', 256)->default('');
			$table->string('username', 32);
			$table->integer('recharge')->unsigned()->default(0);
			$table->integer('recharge_count')->unsigned()->default(0);
			$table->integer('first_recharge')->unsigned()->default(0);
			$table->integer('withdraw')->unsigned()->default(0);
			$table->integer('withdraw_count')->unsigned()->default(0);
			$table->integer('bets')->unsigned()->default(0);
			$table->integer('cancel')->unsigned()->default(0);
			$table->integer('bet_count')->unsigned()->default(0);
			$table->integer('points_self')->unsigned()->default(0);
			$table->integer('points_child')->unsigned()->default(0);
			$table->integer('bonus')->unsigned()->default(0);
			$table->integer('score')->unsigned()->default(0);
			$table->integer('salary')->unsigned()->default(0);
			$table->integer('dividend')->unsigned()->default(0);
			$table->integer('gift')->unsigned()->default(0);
			$table->integer('claim_recharge')->unsigned()->default(0);
			$table->integer('claim_add')->unsigned()->default(0);
			$table->integer('claim_gift')->unsigned()->default(0);
			$table->integer('claim_reduce')->unsigned()->default(0);
			$table->integer('claim_dividend')->unsigned()->default(0);
			$table->integer('claim_salary')->unsigned()->default(0);
			$table->integer('team_recharge')->unsigned()->default(0);
			$table->integer('team_first_recharge')->unsigned()->default(0);
			$table->integer('team_recharge_count')->unsigned()->default(0);
			$table->integer('team_withdraw')->unsigned()->default(0);
			$table->integer('team_withdraw_count')->unsigned()->default(0);
			$table->integer('team_bets')->unsigned()->default(0);
			$table->integer('team_cancel')->unsigned()->default(0);
			$table->integer('team_bet_count')->unsigned()->default(0);
			$table->integer('team_points_self')->unsigned()->default(0);
			$table->integer('team_points_child')->unsigned()->default(0);
			$table->integer('team_bonus')->unsigned()->default(0);
			$table->integer('team_score')->unsigned()->default(0);
			$table->integer('team_salary')->unsigned()->default(0);
			$table->integer('team_dividend')->unsigned()->default(0);
			$table->integer('team_gift')->unsigned()->default(0);
			$table->integer('team_claim_recharge')->unsigned()->default(0);
			$table->integer('team_claim_add')->unsigned()->default(0);
			$table->integer('team_claim_gift')->unsigned()->default(0);
			$table->integer('team_claim_reduce')->unsigned()->default(0);
			$table->integer('team_claim_dividend')->unsigned()->default(0);
			$table->integer('team_claim_salary')->unsigned()->default(0);
			$table->integer('day');
			$table->timestamps();
			$table->index(['top_id','parent_id','day']);
			$table->index(['top_id','rid','day']);
			$table->index(['top_id','day']);
			$table->index(['top_id','user_id','day']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_stat_day');
	}

}
