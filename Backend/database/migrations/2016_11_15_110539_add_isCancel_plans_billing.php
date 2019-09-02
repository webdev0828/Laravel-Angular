<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCancelPlansBilling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans_billing', function (Blueprint $table) {
            if(!Schema::hasColumn('plans_billing', 'isCancel')) {
                $table->enum('isCancel',['0','1'])->default('0');
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
            if(Schema::hasColumn('plans_billing', 'isCancel')) {
                $table->dropColumn('isCancel');
            }
        });
    }
}
