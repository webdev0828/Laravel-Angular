<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignIdColumnTrackDemos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_demos', function (Blueprint $table) {
            if(!Schema::hasColumn('track_demos', 'campaign_id')) {
                $table->integer('campaign_id')->after('user_id')->nullable();
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
        Schema::table('track_demos', function (Blueprint $table) {
            if(Schema::hasColumn('track_demos', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }
}
