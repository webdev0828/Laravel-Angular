<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompetitionAddPublishedDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            if(!Schema::hasColumn('competitions', 'published_date')) {
                $table->date('published_date')->nullable();
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
        Schema::table('competitions', function (Blueprint $table) {
            if(Schema::hasColumn('competitions', 'published_date')) {
                $table->dropColumn('published_date');
            }
        });
    }
}
