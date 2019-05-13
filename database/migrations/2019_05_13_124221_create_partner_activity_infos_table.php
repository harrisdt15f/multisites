<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerActivityInfosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_activity_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 45)->nullable();
            $table->boolean('type')->nullable();
            $table->text('content', 65535)->nullable();
            $table->string('pic_path')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('admin_id')->nullable();
            $table->string('admin_name', 45)->nullable();
            $table->string('redirect_url', 128)->nullable();
            $table->boolean('is_time_interval')->nullable();
            $table->timestamps();
            $table->string('thumbnail_path')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('partner_activity_infos');
    }

}
