<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackendAdminAuditFlowListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backend_admin_audit_flow_lists', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('admin_id')->nullable();
			$table->integer('auditor_id')->nullable();
			$table->text('apply_note', 65535)->nullable();
			$table->text('auditor_note', 65535)->nullable();
			$table->timestamps();
			$table->string('admin_name', 64)->nullable();
			$table->string('auditor_name', 64)->nullable();
			$table->string('username', 64)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('backend_admin_audit_flow_lists');
	}

}
