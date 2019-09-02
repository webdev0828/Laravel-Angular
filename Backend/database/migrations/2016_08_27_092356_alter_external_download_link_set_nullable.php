<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExternalDownloadLinkSetNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `external_download_link` `external_download_link` varchar(255) NULL;");
        DB::statement("ALTER TABLE `track_demos` CHANGE COLUMN `external_download_link` `external_download_link` varchar(255) NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
