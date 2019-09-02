<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\Plans;

class PlansTableSeeder extends Seeder {

    public function run()
    {
        // use the factory to create a Faker\Generator instance
        \DB::table('plans')->truncate();

        Plans::firstOrCreate(array(
            'name' => 'Test The Waters',
            'stripe_plan_key'    => 'free',
            'amount' => 0,
            'discover_demo_limit' => 0,
            'remix_demo_limit' => 0,
            'video_demo_limit' => 0
        ));

        // Plans::firstOrCreate(array(
        //     'name' => 'Premium Gating',
        //     'stripe_plan_key'    => 'premium',
        //     'amount' => 1.99,
        //     'discover_demo_limit' => 0,
        //     'remix_demo_limit' => 0,
        //     'video_demo_limit' => 0
        // ));

        Plans::firstOrCreate(array(
            'name' => 'Infinite Gating',
            'stripe_plan_key'    => 'infinite',
            'amount' => 3.99,
            'discover_demo_limit' => 0,
            'remix_demo_limit' => 0,
            'video_demo_limit' => 0
        ));

        Plans::firstOrCreate(array(
            'name' => 'Gating & Discover',
            'stripe_plan_key'    => 'gating',
            'amount' => 6.99,
            'remix_demo_limit' => 2,
            'video_demo_limit' => 2
        ));

        Plans::firstOrCreate(array(
            'name' => 'Gating, Discover & Releases',
            'stripe_plan_key'    => 'gating2',
            'amount' => 8.99,
            'discover_demo_limit' => 4,
            'remix_demo_limit' => 4,
            'video_demo_limit' => 4
        ));

    }

}