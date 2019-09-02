<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePriceColumnSetText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `competitions` CHANGE COLUMN `price_1` `price_1` varchar(255) COLLATE utf8_unicode_ci ;");
            DB::statement("ALTER TABLE `competitions` CHANGE COLUMN `price_2` `price_2` varchar(255) COLLATE utf8_unicode_ci ;");
            DB::statement("ALTER TABLE `competitions` CHANGE COLUMN `price_3` `price_3` varchar(255) COLLATE utf8_unicode_ci ;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competitions', function (Blueprint $table) {
            //
        });
    }
}
