<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerArticlesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->string('title', 45)->nullable();
            $table->string('summary', 45)->nullable();
            $table->text('content', 65535)->nullable();
            $table->string('search_text', 45)->nullable();
            $table->boolean('is_for_agent')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('audit_flow_id')->nullable();
            $table->integer('add_admin_id')->nullable();
            $table->integer('last_update_admin_id')->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->string('pic_path')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('partner_articles');
    }

}
