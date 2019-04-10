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
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->string('rid', 256);
			$table->bigInteger('balance')->unsigned()->default(0);
			$table->bigInteger('frozen')->unsigned()->default(0);
			$table->boolean('status')->default(0);
			$table->timestamps();
			$table->index(['user_id','frozen']);
			$table->index(['user_id','balance']);
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
