<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOauthAccessTokenProvidersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_access_token_providers', function (Blueprint $table) {
            $table->foreign('oauth_access_token_id')->references('id')->on('oauth_access_tokens')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_access_token_providers', function (Blueprint $table) {
            $table->dropForeign('oauth_access_token_providers_oauth_access_token_id_foreign');
        });
    }

}
