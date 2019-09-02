<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceIdPlanBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('plans_billing', function (Blueprint $table) {
            if(!Schema::hasColumn('plans_billing', 'invoice_id')) {
                $table->string('invoice_id')->nullable();
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
            if(Schema::hasColumn('plans_billing', 'invoice_id')) {
                $table->dropColumn('invoice_id');
            }
        });
    }
}