<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserProfileAddIsartist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if(!Schema::hasColumn('user_profiles', 'isArtist')) {
                $table->enum('isArtist',['0','1'])->default('0');
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
        Schema::table('user_profiles', function (Blueprint $table) {
            if(Schema::hasColumn('user_profiles', 'isArtist')) {
                $table->dropColumn('isArtist');
            }
        });
    }
}
