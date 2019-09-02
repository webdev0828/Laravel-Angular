<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToArtistGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artist_genres', function (Blueprint $table) {
            if(!Schema::hasColumn('artist_genres', 'type')) {
                $table->enum('type', array('label','artist'));
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
        Schema::table('artist_genres', function (Blueprint $table) {
            //
        });
    }
}
