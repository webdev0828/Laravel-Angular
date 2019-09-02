<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypePlayHistoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE play_history_details CHANGE type type ENUM('track','remix','video','campaign');");

        Schema::table('play_history_details', function (Blueprint $table) {
            if(!Schema::hasColumn('play_history_details', 'campaign_id')) {
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
        Schema::table('play_history_details', function (Blueprint $table) {
            if(Schema::hasColumn('play_history_details', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }
}
