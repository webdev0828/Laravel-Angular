<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecretTokenScArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sc_artist', function (Blueprint $table) {
            if(!Schema::hasColumn('sc_artist', 'secret_token')) {
                $table->string('secret_token')->nullable();
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
            if(Schema::hasColumn('sc_artist', 'secret_token')) {
                $table->dropColumn('secret_token');
            }
        });
    }
}
