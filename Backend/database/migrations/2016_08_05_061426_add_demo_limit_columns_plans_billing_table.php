<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDemoLimitColumnsPlansBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans_billing', function (Blueprint $table) {
            if(!Schema::hasColumn('plans_billing', 'discover_demo_limit')) {
               $table->integer('discover_demo_limit');
            }
            if(!Schema::hasColumn('plans_billing', 'remix_demo_limit')) {
               $table->integer('remix_demo_limit');
            }
            if(!Schema::hasColumn('plans_billing', 'video_demo_limit')) {
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
        Schema::table('plans_billing', function (Blueprint $table) {
            
            if(Schema::hasColumn('plans_billing', 'discover_demo_limit')) {
               $table->dropColumn('discover_demo_limit');
            }
            if(Schema::hasColumn('plans_billing', 'remix_demo_limit')) {
               $table->dropColumn('remix_demo_limit');
            }
            if(Schema::hasColumn('plans_billing', 'video_demo_limit')) {
               $table->dropColumn('video_demo_limit');
            }
        });
    }
}
