<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFbPageInArtistProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('artists_profile', function (Blueprint $table) {
            if(!Schema::hasColumn('artists_profile', 'fb_page')) {
                $table->string('fb_page');
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
        Schema::table('artists_profile', function (Blueprint $table) {
            if(Schema::hasColumn('users', 'fb_page')) {
                $table->dropColumn('fb_page');
            }
        });
    }
}
