<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompetitionArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competition_artists', function (Blueprint $table) {
            if(!Schema::hasColumn('competition_artists', 'cover_image')) {
                $table->string('cover_image');
            }

            if(!Schema::hasColumn('competition_artists', 'track_name')) {
                $table->string('track_name');
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
