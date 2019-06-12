<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendMessageNoticesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_message_notices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('type')->comment('类型');
			$table->string('title', 64)->comment('标题');
			$table->text('content', 65535)->comment('描述');
			$table->timestamp('start_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('开始时间');
			$table->dateTime('end_time')->nullable()->comment('结束时间');
			$table->integer('sort');
			$table->boolean('status')->comment('0 禁用 1 启用');
			$table->integer('admin_id')->comment('管理员id');
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
		Schema::drop('frontend_message_notices');
	}

}
