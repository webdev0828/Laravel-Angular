<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_profile', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('users_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name', 100);
            $table->string('first_name', 50);
            $table->string('last_name',50);
            $table->string('country', 50);
            $table->string('city', 50);
            $table->string('website', 100);
            $table->string('genre');
            $table->string('avatar');
            $table->string('cover');
            $table->string('souncloud_url',100);
            $table->string('facebook_url',100);
            $table->string('twitter_url',100);
            $table->string('youtube_url',100);
            $table->string('instagram_url',100);
            $table->text('bio');
            $table->string('slug');
            $table->timestamps();
        });
    }
}
