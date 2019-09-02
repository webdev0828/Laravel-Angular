<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sc_artist', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artist_id');
            $table->string('sc_id');
            $table->string('track_name');
            $table->string('description');
            $table->string('url');
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
        Schema::table('sc_artist', function (Blueprint $table) {
            Schema::drop('sc_artist');
        });
    }
}
