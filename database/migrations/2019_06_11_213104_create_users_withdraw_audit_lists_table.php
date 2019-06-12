<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersWithdrawAuditListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_withdraw_audit_lists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id');
			$table->string('username', 64);
			$table->string('rid', 256)->index('user_withdraw_check_rid_index');
			$table->integer('withdraw_id')->default(0);
			$table->integer('admin_id');
			$table->string('admin_name', 64);
			$table->boolean('status')->default(0);
			$table->string('reason', 64)->default('');
			$table->integer('checked_time')->default(0);
			$table->timestamps();
			$table->index(['top_id','user_id','status'], 'user_withdraw_check_top_id_user_id_status_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_withdraw_audit_lists');
	}

}
