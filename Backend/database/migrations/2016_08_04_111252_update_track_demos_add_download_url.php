<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTrackDemosAddDownloadUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_demos', function (Blueprint $table) {
            if(!Schema::hasColumn('track_demos', 'download_url')) {
                $table->string('download_url')->after('background_file');
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
            if(Schema::hasColumn('track_demos', 'download_url')) {
                $table->dropColumn('download_url');
            }
        });
    }
}
