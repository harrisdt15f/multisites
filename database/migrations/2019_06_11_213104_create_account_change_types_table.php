<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountChangeTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_change_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 32);
			$table->string('sign', 32);
			$table->boolean('in_out')->default(1);
			$table->boolean('amount')->default(1);
			$table->boolean('user_id')->default(1);
			$table->boolean('project_id')->default(1);
			$table->boolean('lottery_id')->default(1);
			$table->boolean('method_id')->default(1);
			$table->boolean('issue')->default(1);
			$table->boolean('from_id')->default(1);
			$table->boolean('from_admin_id')->default(1);
			$table->boolean('to_id')->default(1);
			$table->boolean('frozen_type')->default(1);
			$table->boolean('activity_sign')->default(1);
			$table->integer('admin_id')->default(0);
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
		Schema::drop('account_change_types');
	}

}
