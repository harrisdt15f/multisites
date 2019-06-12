<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackendAdminAuditPasswordsListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backend_admin_audit_passwords_lists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type')->unsigned()->comment('审核类型 1=password, 2=资金密码');
			$table->integer('user_id')->unsigned()->comment('被审核用户的id');
			$table->text('audit_data', 65535)->comment('待审核的数据');
			$table->boolean('status')->default(0)->comment('0:审核中, 1:审核通过, 2:审核拒绝');
			$table->integer('audit_flow_id')->nullable()->comment('提交人 与审核人的记录流程');
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
		Schema::drop('backend_admin_audit_passwords_lists');
	}

}
