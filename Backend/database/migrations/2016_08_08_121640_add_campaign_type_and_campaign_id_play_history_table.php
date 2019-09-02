<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignTypeAndCampaignIdPlayHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('play_history', function (Blueprint $table) {
            if(!Schema::hasColumn('play_history', 'campaign_id')) {
                $table->integer('campaign_id')->after('track_id');
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
        Schema::table('play_history', function (Blueprint $table) {
            if(Schema::hasColumn('play_history', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }
}
