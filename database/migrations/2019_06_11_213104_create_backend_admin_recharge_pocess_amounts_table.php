<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackendAdminRechargePocessAmountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backend_admin_recharge_pocess_amounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('admin_id')->nullable();
			$table->decimal('fund', 10)->nullable()->default(0.00);
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
		Schema::drop('backend_admin_recharge_pocess_amounts');
	}

}
