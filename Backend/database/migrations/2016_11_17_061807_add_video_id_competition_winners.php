<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoIdCompetitionWinners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            if(!Schema::hasColumn('competition_winners', 'video_id')) {
                $table->integer('video_id')->after('position')->nullable();
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
        Schema::table('competition_winners', function (Blueprint $table) {
            if(Schema::hasColumn('competition_winners', 'video_id')) {
                $table->dropColumn('video_id');
            }
        });
    }
}
