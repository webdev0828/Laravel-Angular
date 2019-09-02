<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlanNameColumnInPlansBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans_billing', function (Blueprint $table) {
            DB::statement("ALTER TABLE `plans_billing` CHANGE COLUMN `plan_name` `plan_name` varchar(45) COLLATE utf8_unicode_ci;");
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
            //
        });
    }
}
