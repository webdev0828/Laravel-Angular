<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrackIdSetNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `play_history` CHANGE COLUMN `track_id` `track_id` int(11) NULL;");
        DB::statement("ALTER TABLE `play_history_details` CHANGE COLUMN `track_id` `track_id` int(11) NULL;");
        DB::statement("ALTER TABLE `track_shares` CHANGE COLUMN `track_id` `track_id` int(11) NULL;");
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
