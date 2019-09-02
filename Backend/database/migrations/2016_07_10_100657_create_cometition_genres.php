<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCometitionGenres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competition_id');
            $table->integer('genre_id');
            $table->enum('type', array('parent','sub'));
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
        Schema::drop('competition_genres');
    }
}
