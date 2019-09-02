<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVideoReleasesAddBackgroundImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stm_video_releases', function (Blueprint $table) {
            if(!Schema::hasColumn('stm_video_releases', 'background_file')) {
                $table->string('background_file');
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
        Schema::table('stm_video_releases', function (Blueprint $table) {
            if(Schema::hasColumn('stm_video_releases', 'background_file')) {
                $table->dropColumn('background_file');
            }
        });
    }
}
