<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToArtistsProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artists_profile', function (Blueprint $table) {
            if(!Schema::hasColumn('artists_profile', 'slug')) {
                $table->string('slug');
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
            //
        });
    }
}
