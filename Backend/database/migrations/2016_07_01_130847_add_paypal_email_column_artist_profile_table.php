<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaypalEmailColumnArtistProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artists_profile', function (Blueprint $table) {
            if(!Schema::hasColumn('artists_profile', 'paypal_email')) {
                $table->string('paypal_email')->after('website');
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
        Schema::table('artists_profile', function (Blueprint $table) {
            if(Schema::hasColumn('artists_profile', 'paypal_email')) {
                $table->dropColumn('paypal_email');
            }
        });
    }
}
