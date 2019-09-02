<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSignupTrackDemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_demos', function (Blueprint $table) {
            if(!Schema::hasColumn('track_demos', 'isSignupTrack')) {
                $table->enum('isSignupTrack',['signup'])->nullable();
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
            if(Schema::hasColumn('track_demos', 'isSignupTrack')) {
                $table->dropColumn('isSignupTrack');
            }
        });
    }
}
