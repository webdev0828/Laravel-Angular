<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSettingsAddName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if(!Schema::hasColumn('settings', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
        });

        if(Schema::hasColumn('settings', 'current_status')) {
            DB::statement("ALTER TABLE `settings` CHANGE COLUMN `current_status` `current_status` enum('0', '1') COLLATE utf8_unicode_ci NOT NULL ;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            if(Schema::hasColumn('settings', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
}
