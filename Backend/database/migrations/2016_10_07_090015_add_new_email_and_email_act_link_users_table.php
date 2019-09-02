<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewEmailAndEmailActLinkUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'new_email')) {
                $table->string('new_email');
            }
            if(!Schema::hasColumn('users', 'email_act_link')) {
                $table->string('email_act_link');
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
            if(Schema::hasColumn('users', 'new_email')) {
                $table->dropColumn('new_email');
            }
            if(Schema::hasColumn('users', 'email_act_link')) {
                $table->dropColumn('email_act_link');
            }
        });
    }
}
