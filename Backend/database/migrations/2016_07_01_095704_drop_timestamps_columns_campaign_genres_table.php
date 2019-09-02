<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTimestampsColumnsCampaignGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_genres', function (Blueprint $table) {
            if(Schema::hasColumn('campaign_genres', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if(Schema::hasColumn('campaign_genres', 'updated_at')) {
                $table->dropColumn('updated_at');
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
        Schema::table('campaign_tables', function (Blueprint $table) {
            //
        });
    }
}
