<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemixDemoLimitPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            
            if(!Schema::hasColumn('plans', 'remix_demo_limit')) {
               $table->integer('remix_demo_limit');
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
            if(Schema::hasColumn('plans', 'remix_demo_limit')) {
                $table->dropColumn('remix_demo_limit');
            }
        });
    }
}
