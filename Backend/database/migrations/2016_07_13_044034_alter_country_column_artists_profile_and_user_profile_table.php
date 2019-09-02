<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCountryColumnArtistsProfileAndUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('artists_profile', function (Blueprint $table) {
            if(Schema::hasColumn('artists_profile', 'country')) {
                DB::statement("ALTER TABLE `artists_profile` CHANGE COLUMN `country` `country` varchar(100) COLLATE utf8_unicode_ci NOT NULL;");
            }
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            if(Schema::hasColumn('user_profiles', 'country')) {
                DB::statement("ALTER TABLE `user_profiles` CHANGE COLUMN `country` `country` varchar(100) COLLATE utf8_unicode_ci NOT NULL;");
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
        //
    }
}
