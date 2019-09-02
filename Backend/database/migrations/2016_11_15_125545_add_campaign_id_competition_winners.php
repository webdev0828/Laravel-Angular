<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignIdCompetitionWinners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            if(!Schema::hasColumn('competition_winners', 'campaign_id')) {
                $table->integer('campaign_id')->after('competition_id');
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
        Schema::table('competition_winners', function (Blueprint $table) {
            if(Schema::hasColumn('competition_winners', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }
}
