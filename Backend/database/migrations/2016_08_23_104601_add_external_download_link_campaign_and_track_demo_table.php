<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalDownloadLinkCampaignAndTrackDemoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if(!Schema::hasColumn('campaigns', 'external_download_link')) {
                $table->string('external_download_link')->after('download_url');
            }
        });
        Schema::table('track_demos', function (Blueprint $table) {
            if(!Schema::hasColumn('track_demos', 'external_download_link')) {
                $table->string('external_download_link')->after('download_url');
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
            if(Schema::hasColumn('campaigns', 'external_download_link')) {
                $table->dropColumn('external_download_link');
            }
        });

        Schema::table('track_demos', function (Blueprint $table) {
            if(Schema::hasColumn('track_demos', 'external_download_link')) {
                $table->dropColumn('external_download_link');
            }
        });

    }
}
