<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOauthIdentitiesAddTokenSecret extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_identities', function (Blueprint $table) {
            if(!Schema::hasColumn('oauth_identities', 'token_secret')) {
                $table->string('token_secret')->nullable()->after('access_token');
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
            if(Schema::hasColumn('oauth_identities', 'token_secret')) {
                $table->dropColumn('token_secret');
            }
        });
    }
}
