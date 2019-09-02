<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoDemoLimitColumnPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            if(!Schema::hasColumn('plans', 'video_demo_limit')) {
               $table->integer('video_demo_limit');
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
        Schema::table('plans', function (Blueprint $table) {
            if(Schema::hasColumn('plans', 'video_demo_limit')) {
               $table->dropColumn('video_demo_limit');
            }
        });
    }
}
