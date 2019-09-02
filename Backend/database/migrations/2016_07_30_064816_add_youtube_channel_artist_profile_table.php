<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYoutubeChannelArtistProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artists_profile', function (Blueprint $table) {
            if(!Schema::hasColumn('artists_profile', 'youtube_channel')) {
                $table->string('youtube_channel')->after('website');
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
           if(Schema::hasColumn('artists_profile', 'youtube_channel')) {
                $table->dropColumn('youtube_channel');
            } 
        });
    }
}
