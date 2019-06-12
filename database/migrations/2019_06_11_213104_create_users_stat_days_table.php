<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersStatDaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_stat_days', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id');
			$table->string('rid', 256)->default('');
			$table->string('username', 32);
			$table->string('lottery_id', 32);
			$table->string('method_id', 32);
			$table->integer('bets')->unsigned()->default(0);
			$table->integer('points')->unsigned()->default(0);
			$table->integer('bonus')->unsigned()->default(0);
			$table->integer('canceled')->unsigned()->default(0);
			$table->integer('team_bets')->unsigned()->default(0);
			$table->integer('team_points')->unsigned()->default(0);
			$table->integer('team_bonus')->unsigned()->default(0);
			$table->integer('team_canceled')->unsigned()->default(0);
			$table->integer('day');
			$table->timestamps();
			$table->index(['top_id','parent_id','day'], 'user_sale_day_top_id_parent_id_day_index');
			$table->index(['top_id','day'], 'user_sale_day_top_id_day_index');
			$table->index(['top_id','user_id','day'], 'user_sale_day_top_id_user_id_day_index');
			$table->index(['top_id','rid','day'], 'user_sale_day_top_id_rid_day_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_stat_days');
	}

}
