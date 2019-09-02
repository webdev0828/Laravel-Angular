<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlayHistoryDetailsAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('play_history_details', function (Blueprint $table) {
             if(!Schema::hasColumn('play_history_details', 'type')) {
                $table->enum('type', array('track','remix','video'))->after('track_id');
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
        Schema::table('play_history_details', function (Blueprint $table) {
            //
        });
    }
}
