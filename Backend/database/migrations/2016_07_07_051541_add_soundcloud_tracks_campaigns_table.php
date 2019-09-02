<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoundcloudTracksCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if(!Schema::hasColumn('campaigns', 'soundcloud_tracks')) {
               $table->string('soundcloud_tracks')->after('background_file');
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
        Schema::table('campaigns', function (Blueprint $table) {
            if(Schema::hasColumn('campaigns', 'soundcloud_tracks')) {
               $table->dropColumn('soundcloud_tracks');
            }
        });
    }
}
