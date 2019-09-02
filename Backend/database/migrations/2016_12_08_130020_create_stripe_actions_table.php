<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->timestamp('actionable_date')->nullable();
            $table->string('stripe_customer_id');
            $table->string('subscription_id');
            $table->string('action');
            $table->string('change_plan_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stripe_actions');
    }
}
