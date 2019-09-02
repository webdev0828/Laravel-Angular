<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('track_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('artist_id');
            $table->enum('user_type', array('stm_user','artist'))->nullable();
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
        Schema::drop('download_history');
    }
}
