<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            if(!Schema::hasColumn('competitions', 'cover_image')) {
                $table->string('cover_image');
            }

            if(!Schema::hasColumn('competitions', 'price_1')) {
                $table->double('price_1')->nullable();;
            }

            if(!Schema::hasColumn('competitions', 'price_2')) {
                $table->double('price_2')->nullable();
            }

            if(!Schema::hasColumn('competitions', 'price_3')) {
                $table->double('price_3')->nullable();
            }

            if(!Schema::hasColumn('competitions', 'status')) {
                $table->boolean('status');
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
        //
    }
}
