<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountChangeTypeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_change_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32)->nullable();
            $table->string('sign', 32)->nullable();
            $table->boolean('in_out')->nullable();
            $table->boolean('type')->nullable();
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
        Schema::drop('account_change_type');
    }

}
