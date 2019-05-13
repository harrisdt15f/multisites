<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegisterLinkUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('register_link_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('register_link_id')->unsigned()->nullable()->index('create_user_link_user_create_user_link_id_index');
			$table->integer('user_id')->unsigned()->index('create_user_link_user_user_id_index');
			$table->string('url')->comment('url内容');
			$table->string('username', 16);
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
		Schema::drop('register_link_users');
	}

}
