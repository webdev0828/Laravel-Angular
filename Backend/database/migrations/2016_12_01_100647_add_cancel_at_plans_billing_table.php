<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelAtPlansBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans_billing', function (Blueprint $table) {
            if(!Schema::hasColumn('plans_billing', 'cancel_at')) {
                $table->dateTime('cancel_at')->nullable();
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
            if(Schema::hasColumn('plans_billing', 'cancel_at')) {
                $table->dropColumn('cancel_at');
            }
        });
    }
}
