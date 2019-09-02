<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('track_name');
            $table->string('slug');
            $table->text('genres');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->dateTime('announcement_date')->nullable();
            $table->text('winner_artist');
            $table->string('original_track');
            $table->string('stem_file');
            $table->text('description');
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
        Schema::drop('competitions');
    }
}
