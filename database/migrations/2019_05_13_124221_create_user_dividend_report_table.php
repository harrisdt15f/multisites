<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserDividendReportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_dividend_report', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sign')->index();
			$table->integer('top_id');
			$table->integer('from_id');
			$table->integer('user_id');
			$table->text('contract', 65535)->nullable();
			$table->integer('did')->default(0);
			$table->boolean('instead')->default(0);
			$table->string('flag', 20)->default('');
			$table->dateTime('from_time');
			$table->dateTime('to_time');
			$table->string('username', 32);
			$table->decimal('amount', 15, 4)->default(0.0000);
			$table->decimal('real_amount', 15, 4)->default(0.0000);
			$table->decimal('bets', 15, 4)->default(0.0000);
			$table->decimal('bonus', 15, 4)->default(0.0000);
			$table->decimal('points', 15, 4)->default(0.0000);
			$table->decimal('brokerage', 15, 4)->default(0.0000);
			$table->decimal('gift', 15, 4)->default(0.0000);
			$table->decimal('salary', 15, 4)->default(0.0000);
			$table->decimal('profit', 15, 4)->default(0.0000);
			$table->integer('rate')->default(0);
			$table->integer('process_time')->default(0);
			$table->integer('verify_time')->default(0);
			$table->boolean('speed')->default(0);
			$table->timestamps();
			$table->index(['user_id','verify_time']);
			$table->index(['from_id','verify_time']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_dividend_report');
	}

}
