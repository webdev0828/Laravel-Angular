<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genres', function (Blueprint $table) {
            if(!Schema::hasColumn('genres', 'parent_id')) {
                $table->integer('parent_id')->nullable();
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
       Schema::table('genres', function (Blueprint $table) {
            if(Schema::hasColumn('genres', 'parent_id')) {
               $table->dropColumn('parent_id');
            }
        });
    }
}
