<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnDownloadHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('download_history', function (Blueprint $table) {
            if(!Schema::hasColumn('download_history', 'type')) {
                $table->enum('type',['track','campaign'])->after('user_type')->nullable();
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
        Schema::table('download_history', function (Blueprint $table) {
            if(Schema::hasColumn('download_history', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}
