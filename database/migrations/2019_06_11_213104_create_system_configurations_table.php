<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemConfigurationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_configurations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->nullable()->default(0);
			$table->integer('pid')->comment('父类id, 顶级为0');
			$table->string('sign', 32)->index('sys_configures_sign_index')->comment('sign 标识');
			$table->string('name', 32)->comment('标题');
			$table->string('description', 128)->nullable()->comment('描述');
			$table->string('value', 128)->nullable()->comment('配置选项value');
			$table->integer('add_admin_id')->default(0)->comment('添加人, 系统添加为0');
			$table->integer('last_update_admin_id')->default(0)->comment('上次更改人id');
			$table->boolean('status')->default(0)->comment('0 禁用 1 启用');
			$table->boolean('display')->nullable()->default(1);
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
		Schema::drop('system_configurations');
	}

}
