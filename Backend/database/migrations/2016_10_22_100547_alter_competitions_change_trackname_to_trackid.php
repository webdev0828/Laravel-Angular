<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompetitionsChangeTracknameToTrackid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            
            if(Schema::hasColumn('competitions', 'track_name')) {
                DB::statement("ALTER TABLE `competitions` CHANGE COLUMN `track_name` `track_id` int(11) NULL;");
            }
        
            if(Schema::hasColumn('competitions', 'winner_artist')) {
                $table->dropColumn('winner_artist');
            }

            if(Schema::hasColumn('competitions', 'original_track')) {
                $table->dropColumn('original_track');
            }

            if(Schema::hasColumn('competitions', 'name')) {
                $table->dropColumn('name');
            }

            if(Schema::hasColumn('competitions', 'cover_image')) {
                $table->dropColumn('cover_image');
            }

            if(Schema::hasColumn('competitions', 'genres')) {
                $table->dropColumn('genres');
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
