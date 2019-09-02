<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailWebNotificationUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'email_notification')) {
                $table->enum('email_notification',[0,1])->default(1);
            }
            if(!Schema::hasColumn('users', 'web_notification')) {
                $table->enum('web_notification',[0,1])->default(1);;
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
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users', 'email_notification')) {
                $table->dropColumn('email_notification');
            }
            if(Schema::hasColumn('users', 'web_notification')) {
                $table->dropColumn('web_notification');
            }
        });
    }
}
