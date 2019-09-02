<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScIdInCompetitionArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competition_artists', function (Blueprint $table) {
            if(!Schema::hasColumn('competition_artists', 'sc_id')) {
                $table->string('sc_id');
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
        Schema::table('competition_artists', function (Blueprint $table) {
            if(Schema::hasColumn('competition_artists', 'sc_id')) {
                $table->dropColumn('sc_id');
            }
        });
    }
}
