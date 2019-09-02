<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackDemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_demos', function (Blueprint $table) {
            $table->increments('id')->nullable(false);
            $table->string('track_name')->nullable();
            $table->text('url');
            $table->integer('user_id')->nullable();
            $table->enum('type', ['discover', 'video'])->nullable();
            $table->enum('status', ['approved', 'rejected','pending'])->nullable();
            $table->dateTime('status_date')->nullable();
            $table->enum('source', ['sc', 'local'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('track_demos');
    }
}
