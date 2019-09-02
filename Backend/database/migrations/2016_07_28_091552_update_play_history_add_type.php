<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlayHistoryAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('play_history', function (Blueprint $table) {
            if(!Schema::hasColumn('play_history', 'type')) {
                $table->enum('type', array('track','remix','video'))->after('artist_id');
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
        Schema::table('play_history', function (Blueprint $table) {
            //
        });
    }
}
