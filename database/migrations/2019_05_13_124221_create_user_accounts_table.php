<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->bigInteger('balance')->unsigned()->default(0);
			$table->bigInteger('frozen')->unsigned()->default(0);
			$table->boolean('status')->default(0);
			$table->timestamps();
			$table->index(['user_id','balance']);
            $table->index(['user_id', 'frozen']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_accounts');
	}

}
