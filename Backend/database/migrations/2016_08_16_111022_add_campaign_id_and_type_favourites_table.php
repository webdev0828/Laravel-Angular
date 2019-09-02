<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignIdAndTypeFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favourites', function (Blueprint $table) {
            DB::statement("ALTER TABLE favourites CHANGE type type ENUM('track','remix','campaign');");
            DB::statement("ALTER TABLE `favourites` CHANGE COLUMN `track_id` `track_id` int(11) NULL;");
            if(!Schema::hasColumn('favourites', 'campaign_id')) {
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
        Schema::table('favourites', function (Blueprint $table) {
            if(Schema::hasColumn('favourites', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }
}
