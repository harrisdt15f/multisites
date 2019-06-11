<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendPageBannersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_page_banners', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 45)->nullable();
			$table->text('content', 65535)->nullable();
			$table->string('pic_path', 128)->nullable();
			$table->string('thumbnail_path', 128)->nullable();
			$table->boolean('type')->nullable()->comment('1内部 2活动');
			$table->string('redirect_url', 128)->nullable();
			$table->integer('activity_id')->nullable();
			$table->boolean('status')->nullable();
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->integer('sort')->unsigned()->nullable();
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
		Schema::drop('frontend_page_banners');
	}

}
