<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendUsersAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_users_accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->bigInteger('balance')->unsigned()->default(0);
			$table->bigInteger('frozen')->unsigned()->default(0);
			$table->boolean('status')->default(0);
			$table->timestamps();
			$table->index(['user_id','balance'], 'user_accounts_user_id_balance_index');
			$table->index(['user_id','frozen'], 'user_accounts_user_id_frozen_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('frontend_users_accounts');
	}

}
