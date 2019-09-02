<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStmVideoReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stm_video_releases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artist_id');
            $table->string('track_name');
            $table->string('url');
            $table->string('artwork_file');
            $table->string('download_link');
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
        Schema::drop('stm_video_releases');
    }
}
