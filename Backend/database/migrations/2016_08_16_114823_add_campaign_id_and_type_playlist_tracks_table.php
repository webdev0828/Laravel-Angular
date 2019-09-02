<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignIdAndTypePlaylistTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlist_tracks', function (Blueprint $table) {
            DB::statement("ALTER TABLE playlist_tracks CHANGE type type ENUM('track','remix','video','campaign');");
            DB::statement("ALTER TABLE `playlist_tracks` CHANGE COLUMN `track_id` `track_id` int(11) NULL;");
            if(!Schema::hasColumn('playlist_tracks', 'campaign_id')) {
                $table->integer('campaign_id')->after('track_id')->nullable();
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
        Schema::table('playlist_tracks', function (Blueprint $table) {
            if(Schema::hasColumn('playlist_tracks', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }
}
