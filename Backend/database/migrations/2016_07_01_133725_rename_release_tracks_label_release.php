<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameReleaseTracksLabelRelease extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('release_tracks', function (Blueprint $table) {
            Schema::rename('release_tracks','label_release');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('release_tracks', function (Blueprint $table) {
            Schema::rename('label_release','release_tracks');
        });
    }
}
