<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStmReleasesGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stm_releases_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stm_releases_id');
            $table->integer('genre_id');
            $table->enum('type', array('genre','sub_genre'));
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
        Schema::drop('stm_releases_genres');
    }
}
