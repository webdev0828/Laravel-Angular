<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignIdTrackSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_shares', function (Blueprint $table) {
            if(!Schema::hasColumn('track_shares', 'campaign_id')) {
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
        Schema::table('track_shares', function (Blueprint $table) {
            if(Schema::hasColumn('track_shares', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }
}
