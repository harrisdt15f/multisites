<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendUsersBankCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frontend_users_bank_cards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->index('user_bank_cards_user_id_index');
			$table->integer('parent_id');
			$table->integer('top_id');
			$table->string('rid', 128);
			$table->string('username', 64);
			$table->string('bank_sign', 32);
			$table->string('bank_name', 64);
			$table->string('owner_name', 128)->index('user_bank_cards_owner_name_index');
			$table->string('card_number', 64)->index('user_bank_cards_card_number_index');
			$table->string('province_id', 64);
			$table->string('city_id', 64);
			$table->string('branch', 64);
			$table->boolean('status')->default(0);
			$table->integer('admin_id')->default(0);
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
		Schema::drop('frontend_users_bank_cards');
	}

}
