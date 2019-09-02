<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToCampaignGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_genres', function (Blueprint $table) {
            if(!Schema::hasColumn('campaign_genres', 'type')) {
                $table->enum('type', array('parent','sub'))
                      ->after('genre_id')
                      ->nullable();
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
        Schema::table('campaign_genres', function($table)
        {
            if(Schema::hasColumn('campaign_genres', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}
