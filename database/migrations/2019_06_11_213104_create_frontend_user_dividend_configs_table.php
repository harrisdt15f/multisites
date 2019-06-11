<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendUserDividendConfigsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_user_dividend_configs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign')->index('user_dividend_config_sign_index');
			$table->integer('top_id');
			$table->integer('parent_id');
			$table->integer('user_id');
			$table->string('username', 20);
			$table->text('contract', 65535)->nullable();
			$table->text('temp', 65535)->nullable();
			$table->boolean('verify')->default(0);
			$table->boolean('status')->default(0);
			$table->integer('verify_time')->default(0);
			$table->timestamps();
			$table->index(['parent_id','user_id'], 'user_dividend_config_parent_id_user_id_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('frontend_user_dividend_configs');
	}

}
