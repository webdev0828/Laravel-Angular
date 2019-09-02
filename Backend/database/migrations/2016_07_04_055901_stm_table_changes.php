<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StmTableChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favourites', function (Blueprint $table) {
            if(!Schema::hasColumn('favourites', 'created_at') && !Schema::hasColumn('favourites', 'updated_at')) {
               $table->timestamps(); 
            }
        });
        Schema::table('likes', function (Blueprint $table) {
            if(!Schema::hasColumn('likes', 'created_at') && !Schema::hasColumn('likes', 'updated_at')) {
               $table->timestamps(); 
            }
        });
        Schema::table('artist_genres', function (Blueprint $table) {
            if(!Schema::hasColumn('artist_genres', 'created_at') && !Schema::hasColumn('artist_genres', 'updated_at')) {
               $table->timestamps(); 
            }
        });
        Schema::table('campaign_genres', function (Blueprint $table) {
            if(!Schema::hasColumn('campaign_genres', 'created_at') && !Schema::hasColumn('campaign_genres', 'updated_at')) {
               $table->timestamps(); 
            }
        });
        Schema::table('plans', function (Blueprint $table) {
            if(!Schema::hasColumn('plans', 'created_at') && !Schema::hasColumn('plans', 'updated_at')) {
               $table->timestamps(); 
            }

            if(!Schema::hasColumn('plans', 'amount')) {
               $table->float('amount');
            }

            if(!Schema::hasColumn('plans', 'discover_demo_limit')) {
               $table->integer('discover_demo_limit');
            }

            if(!Schema::hasColumn('plans', 'stripe_plan_key')) {
                DB::statement("ALTER TABLE `plans` CHANGE COLUMN `stripe_plan_id` `stripe_plan_key` varchar(45) COLLATE utf8_unicode_ci;");   
            }
        });

        Schema::table('campaigns', function (Blueprint $table) {
            if(Schema::hasColumn('campaigns', 'type')) {
               DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `type` `type` enum('original','remix') COLLATE utf8_unicode_ci NOT NULL;");   
            }

            // if(!Schema::hasColumn('plans', 'stripe_plan_key')) {
            //     DB::statement("ALTER TABLE `plans` CHANGE COLUMN `stripe_plan_id` `stripe_plan_key` varchar(45) COLLATE utf8_unicode_ci;");   
            // }
            if(Schema::hasColumn('campaigns', 'track_id')) {
                    $table->dropColumn('track_id');
                }
        });
        Schema::table('competitions', function (Blueprint $table) {
            if(Schema::hasColumn('competitions', 'status')) {
               DB::statement("ALTER TABLE `competitions` CHANGE COLUMN `status` `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL;");   
            }

            // if(!Schema::hasColumn('plans', 'stripe_plan_key')) {
            //     DB::statement("ALTER TABLE `plans` CHANGE COLUMN `stripe_plan_id` `stripe_plan_key` varchar(45) COLLATE utf8_unicode_ci;");   
            // }
        });
        Schema::table('top_items', function (Blueprint $table) {
            if(Schema::hasColumn('top_items', 'object_type')) {
               DB::statement("ALTER TABLE `top_items` CHANGE COLUMN `object_type` `object_type` enum('spotlight_video','spotlight_discover','admin_video') COLLATE utf8_unicode_ci NOT NULL;");   
            }
        });

        Schema::table('track_demos', function (Blueprint $table) {
            if(Schema::hasColumn('track_demos', 'type')) {
               DB::statement("ALTER TABLE `track_demos` CHANGE COLUMN `type` `type` enum('discover','video','remix') COLLATE utf8_unicode_ci NOT NULL;");   
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
