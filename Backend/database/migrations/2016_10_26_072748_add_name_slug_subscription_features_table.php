<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameSlugSubscriptionFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_features', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_features', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('subscription_features', 'slug')) {
                $table->string('slug')->after('description');
            }
        });
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_features', function (Blueprint $table) {
            if(Schema::hasColumn('subscription_features', 'name')) {
                $table->dropColumn('name');
            }
            if(Schema::hasColumn('subscription_features', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
}
