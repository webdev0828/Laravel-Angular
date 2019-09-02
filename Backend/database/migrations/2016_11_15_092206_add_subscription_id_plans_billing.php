<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionIdPlansBilling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans_billing', function (Blueprint $table) {
            if(!Schema::hasColumn('plans_billing', 'subscription_id')) {
                $table->string('subscription_id')->nullable();
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
            if(Schema::hasColumn('plans_billing', 'subscription_id')) {
                $table->dropColumn('subscription_id');
            }
        });
    }
}
