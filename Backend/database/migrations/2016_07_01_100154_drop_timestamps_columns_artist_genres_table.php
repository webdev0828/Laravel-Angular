<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTimestampsColumnsArtistGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artist_genres', function (Blueprint $table) {
            if(Schema::hasColumn('artist_genres', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if(Schema::hasColumn('artist_genres', 'updated_at')) {
                $table->dropColumn('updated_at');
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
