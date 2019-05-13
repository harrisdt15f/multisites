<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSalaryConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_salary_config', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id');
			$table->string('parent_username', 64);
			$table->string('username', 64);
			$table->boolean('user_type')->default(1);
			$table->text('contract', 65535);
			$table->boolean('status')->default(1);
			$table->timestamps();
			$table->index(['sign','user_id']);
			$table->index(['top_id','parent_id','user_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_salary_config');
	}

}
