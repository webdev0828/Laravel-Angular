<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropOrderTransactionsRecommendedTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            Schema::dropIfExists('orders');
        });
        Schema::table('recommended_tracks', function (Blueprint $table) {
            Schema::dropIfExists('recommended_tracks');
        });
        Schema::table('transactions', function (Blueprint $table) {
            Schema::dropIfExists('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
