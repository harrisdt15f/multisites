<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerCategoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 45)->nullable();
            $table->integer('parent')->nullable();
            $table->string('template', 45)->nullable();
            $table->integer('platform_id')->nullable();
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
        Schema::drop('partner_category');
    }

}
