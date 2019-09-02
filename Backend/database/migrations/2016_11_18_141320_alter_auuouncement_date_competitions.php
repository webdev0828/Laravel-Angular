<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuuouncementDateCompetitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
      
               DB::statement("ALTER TABLE `competitions` CHANGE COLUMN `announcement_date` `announcement_date` varchar(250) NULL;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
