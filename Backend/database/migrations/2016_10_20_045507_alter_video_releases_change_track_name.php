<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVideoReleasesChangeTrackName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stm_video_releases', function (Blueprint $table) {

            if(Schema::hasColumn('stm_video_releases', 'track_name')) {
                DB::statement("ALTER TABLE `stm_video_releases` CHANGE COLUMN `track_name` `track_id` int(11) NULL;");
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
        //
    }
}
