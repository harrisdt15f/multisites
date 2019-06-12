<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersRechargeHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_recharge_histories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('user_name', 45)->nullable();
			$table->boolean('is_tester')->nullable();
			$table->integer('top_agent')->nullable();
			$table->boolean('channel')->nullable()->comment('1 银行 2 微信支付宝等等');
			$table->integer('payment_id')->nullable()->comment('支付通道id    bank_id');
			$table->decimal('amount', 10)->nullable()->comment('充值金额');
			$table->string('company_order_num', 45)->nullable()->comment('订单号');
			$table->string('third_party_order_num', 45)->nullable();
			$table->boolean('deposit_mode')->nullable()->comment('1人工充值 0 自动');
			$table->decimal('real_amount', 10)->nullable()->comment('实际支付金额');
			$table->decimal('fee', 10)->nullable()->comment('手续费');
			$table->integer('audit_flow_id')->nullable();
			$table->boolean('status')->nullable()->comment('0正在充值 1充值成功 2充值失败 10待审核 11审核通过 12 审核拒绝');
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
		Schema::drop('users_recharge_histories');
	}

}
