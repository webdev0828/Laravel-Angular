<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugMoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moods', function (Blueprint $table) {
            if(!Schema::hasColumn('moods', 'slug')) {
                $table->string('slug', 100)->after('description');
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
        Schema::table('moods', function (Blueprint $table) {
            if(Schema::hasColumn('moods', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
}
