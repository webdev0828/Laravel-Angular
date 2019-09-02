<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScIdColumnTrackDemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_demos', function (Blueprint $table) {
            if(!Schema::hasColumn('track_demos', 'sc_id')) {
                $table->string('sc_id')->nullable();
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
            if(Schema::hasColumn('track_demos', 'sc_id')) {
                $table->dropColumn('sc_id');
            }
        });
    }
}
