<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('track_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('track_name', 100);
            $table->string('slug', 100);
            $table->string('genres');
            $table->string('souncloud_terms');
            $table->string('facebook_terms');
            $table->string('twitter_terms');
            $table->string('youtube_terms');
            $table->string('instagram_terms');
            $table->string('mp3_file');
            $table->string('artwork_file');
            $table->string('background_file');
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
        Schema::drop('campaigns');
    }
}
