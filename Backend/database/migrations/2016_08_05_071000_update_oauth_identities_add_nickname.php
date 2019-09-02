<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOauthIdentitiesAddNickname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_identities', function (Blueprint $table) {
            if(!Schema::hasColumn('oauth_identities', 'nick_name')) {
                $table->string('nick_name')->after('user_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_identities', function (Blueprint $table) {
            if(Schema::hasColumn('oauth_identities', 'nick_name')) {
                $table->dropColumn('nick_name');
            }
        });
    }
}
