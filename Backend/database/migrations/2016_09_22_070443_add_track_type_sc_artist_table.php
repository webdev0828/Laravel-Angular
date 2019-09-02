<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackTypeScArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sc_artist', function (Blueprint $table) {
            if(!Schema::hasColumn('sc_artist', 'track_type')) {
                $table->string('track_type')->nullable()->after('track_name');
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
        Schema::table('sc_artist', function (Blueprint $table) {
            if(Schema::hasColumn('sc_artist', 'track_type')) {
                $table->dropColumn('track_type');
            }
        });
    }
}

