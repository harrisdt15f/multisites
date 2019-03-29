<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parter_logs_api', function (Blueprint $t) {
            $t->increments('id');
            $t->text('description', 65535)->nullable();
            $t->string('origin', 200)->nullable();
            $t->enum('type', ['log','store','change','delete']);
            $t->enum('result', ['success','neutral','failure']);
            $t->enum('level', ['emergency','alert','critical','error','warning','notice','info','debug']);
            $t->string('token', 100)->nullable();
            $t->ipAddress('ip');
            $t->string('ips', 200)->nullable();
            $t->integer('user_id')->nullable();
            $t->string('session', 100)->nullable();
            $t->string('lang', 50)->nullable();
            $t->string('device', 20)->nullable();
            $t->string('os', 20)->nullable();
            $t->string('os_version', 50)->nullable();
            $t->string('browser', 50)->nullable();
            $t->string('bs_version', 50)->nullable();
            $t->boolean('device_type')->nullable();
            $t->string('robot', 50)->nullable();
            $t->string('user_agent', 200)->nullable();
            $t->text('inputs', 65535)->nullable();
            $t->text('route', 65535)->nullable();
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
        Schema::dropIfExists('parter_logs_api');
    }
}
