<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysProvinceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_province', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 32)->comment('标题');
			$table->integer('code')->comment('邮编');
			$table->boolean('status')->default(0)->comment('0 禁用 1 启用');
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
		Schema::drop('sys_province');
	}

}
