<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScTrackGenres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sc_track_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sc_track_id');
            $table->integer('genre_id');
            // $table->enum('type', array('parent','sub'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sc_track_genres');
    }
}
