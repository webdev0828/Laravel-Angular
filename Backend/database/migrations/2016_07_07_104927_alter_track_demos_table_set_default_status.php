<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrackDemosTableSetDefaultStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_demos', function (Blueprint $table) {
            if(Schema::hasColumn('track_demos', 'status')) {
                DB::statement("ALTER TABLE `track_demos` CHANGE COLUMN `status` `status` enum('approved', 'rejected','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending' ;");
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
            //
        });
    }
}
