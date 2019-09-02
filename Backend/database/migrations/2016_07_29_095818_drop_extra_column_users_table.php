<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropExtraColumnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
                        
            if(Schema::hasColumn('users', 'country')) {
                $table->dropColumn('country');
            }
            if(Schema::hasColumn('users', 'image')) {
                $table->dropColumn('image');
            }
            if(Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
            if(Schema::hasColumn('users', 'website')) {
                $table->dropColumn('website');
            }
            if(Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');

            }if(Schema::hasColumn('users', 'genre')) {
                $table->dropColumn('genre');
            }
            if(Schema::hasColumn('users', 'subscription')) {
                $table->dropColumn('subscription');
            }if(Schema::hasColumn('users', 'cover')) {
                $table->dropColumn('cover');
            }
            if(Schema::hasColumn('users', 'souncloud_url')) {
                $table->dropColumn('souncloud_url');
            }
            if(Schema::hasColumn('users', 'facebook_url')) {
                $table->dropColumn('facebook_url');
            }
            if(Schema::hasColumn('users', 'twitter_url')) {
                $table->dropColumn('twitter_url');
            }if(Schema::hasColumn('users', 'youtube_url')) {
                $table->dropColumn('youtube_url');
            }
            if(Schema::hasColumn('users', 'instagram_url')) {
                $table->dropColumn('instagram_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
