<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReleaseTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('track_name');
            $table->string('record_label');
            $table->string('purchase_link');
            $table->string('album_image');
            $table->string('genres');
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
        Schema::drop('release_tracks');
    }
}
