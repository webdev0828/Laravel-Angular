<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlaylistAddRemixType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlist_tracks', function (Blueprint $table) {
            if(Schema::hasColumn('playlist_tracks', 'type')) {
                DB::statement("ALTER TABLE `playlist_tracks` CHANGE COLUMN `type` `type` enum('track', 'remix', 'video') COLLATE utf8_unicode_ci NOT NULL ;");
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
