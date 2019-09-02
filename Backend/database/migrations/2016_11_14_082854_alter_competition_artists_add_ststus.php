<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompetitionArtistsAddStstus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competition_artists', function (Blueprint $table) {
            if(!Schema::hasColumn('competition_artists', 'status')) {
                $table->enum('status',['bad', 'good', 'finalist'])->nullable();
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
            if(Schema::hasColumn('competition_artists', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}
