<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_shares', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('track_id');
            $table->integer('user_id')->nullable();
            $table->integer('artist_id');
            $table->enum('user_type', array('stm_user','artist'))->nullable();
            $table->string('share_action');
            $table->string('share_type');
            $table->string('provider_user_id')->nullable();
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
        Schema::drop('track_shares');
    }
}
