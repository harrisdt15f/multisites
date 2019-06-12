<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendActivityContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_activity_contents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 45)->nullable();
			$table->text('content', 65535)->nullable();
			$table->string('pic_path')->nullable();
			$table->string('icon_path')->nullable();
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->boolean('status')->nullable();
			$table->integer('admin_id')->nullable();
			$table->string('admin_name', 45)->nullable();
			$table->string('redirect_url', 128)->nullable();
			$table->boolean('is_time_interval')->nullable();
			$table->string('thumbnail_path')->nullable();
			$table->integer('sort');
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
		Schema::drop('frontend_activity_contents');
	}

}
