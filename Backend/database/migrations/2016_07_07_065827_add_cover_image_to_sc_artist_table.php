<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverImageToScArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sc_artist', function (Blueprint $table) {
            if(!Schema::hasColumn('sc_artist', 'cover_image')) {
               $table->string('cover_image')->after('description');
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
        Schema::table('sc_artist', function (Blueprint $table) {
            if(Schema::hasColumn('sc_artist', 'cover_image')) {
               $table->dropColumn('cover_image');
            }
        });
    }
}
