<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('fname');
            $table->string('lname');
            $table->string('name');
            $table->string('soundcloud_url');
            $table->string('facebook_url');
            $table->string('twitter_url');
            $table->string('youtube_url');
            $table->string('instagram_url');
            $table->string('bio');
            $table->string('image');
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
        Schema::drop('admin_users');
    }
}
