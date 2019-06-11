<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendLotteryRedirectBetListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_lottery_redirect_bet_lists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('lotteries_id', 45)->nullable();
			$table->string('pic_path', 128)->nullable();
			$table->integer('sort')->unsigned()->nullable();
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
		Schema::drop('frontend_lottery_redirect_bet_lists');
	}

}
