<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTbleTrackDemosAddDripFeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('track_demos', 'status')) {
            DB::statement("ALTER TABLE `track_demos` CHANGE COLUMN `status` `status` enum('approved', 'rejected', 'pending', 'dripfeed') COLLATE utf8_unicode_ci NOT NULL ;");
        }
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
