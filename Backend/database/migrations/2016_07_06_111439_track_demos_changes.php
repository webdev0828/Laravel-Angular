<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrackDemosChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('track_demos');

        Schema::create('track_demos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('track_name', 100);
            $table->enum('type', array('discover','remix'));
            $table->enum('status', ['approved', 'rejected','pending'])->nullable();
            $table->string('slug', 100);
            $table->string('artist_links');
            $table->string('souncloud_terms');
            $table->string('facebook_terms');
            $table->string('twitter_terms');
            $table->string('youtube_terms');
            $table->string('instagram_terms');
            $table->string('mp3_file');
            $table->string('cover_image');
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
        Schema::drop('track_demos');
    }
}
