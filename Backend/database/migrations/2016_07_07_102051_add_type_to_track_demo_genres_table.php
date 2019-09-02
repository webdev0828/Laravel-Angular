<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToTrackDemoGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_demo_genres', function (Blueprint $table) {
            if(!Schema::hasColumn('track_demo_genres', 'type')) {
                $table->enum('type', array('parent','sub'))->after('genre_id');
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
        Schema::table('track_demo_genres', function (Blueprint $table) {
            if(Schema::hasColumn('track_demo_genres', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}
