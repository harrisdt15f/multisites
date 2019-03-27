<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogTableEg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $t) {
            $t->increments('id');
            $t->text('description')->nullable();
            $t->string('origin', 200)->nullable();
            $t->enum('type', ['log', 'store', 'change', 'delete']);
            $t->enum('result', ['success', 'neutral', 'failure']);
            $t->enum('level', ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug']);
            $t->string('token', 100)->nullable();
            $t->ipAddress('ip');
            $t->string('user_agent', 200)->nullable();
            $t->string('session', 100)->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
