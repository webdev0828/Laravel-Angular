<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDownloadableStremableSharingScArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sc_artist', function (Blueprint $table) {
            if(!Schema::hasColumn('sc_artist', 'downloadable')) {
                $table->enum('downloadable', array('0','1'))->nullable();
            }
            if(!Schema::hasColumn('sc_artist', 'streamable')) {
                $table->enum('streamable', array('0','1'))->nullable();
            }
            if(!Schema::hasColumn('sc_artist', 'sharing')) {
                $table->enum('sharing', array('public','private'))->nullable();
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

            if(Schema::hasColumn('sc_artist', 'downloadable')) {
                $table->dropColumn('downloadable');
            }
            
            if(Schema::hasColumn('sc_artist', 'streamable')) {
                $table->dropColumn('streamable');
            }

            if(Schema::hasColumn('sc_artist', 'sharing')) {
                $table->dropColumn('sharing');
            }
        });
    }
}
