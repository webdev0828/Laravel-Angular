<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\SubscriptionFeature;
use App\PlanFeature;

class FeaturesTableSeeder extends Seeder {

    public function run()
    {
        \DB::table('subscription_features')->truncate();
        \DB::table('plan_features')->truncate();

        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Subscription price lock',
            'description'    => 'Subscription price lock',
            'slug' => 'sub_price_lock'
        ));
        
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Gating options',
            'description'    => 'Gating options',
            'slug' => 'gating_options'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Donate button',
            'description'    => 'Donate button',
            'slug' => 'donate_button'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Ability to join remix competitions',
            'description'    => 'Ability to join remix competitions',
            'slug' => 'join_remix_comp'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Ability to submit your tracks to Discover',
            'description'    => 'Ability to submit your tracks to Discover',
            'slug' => 'submit_discover'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Ability to submit your tracks to Repost',
            'description'    => 'Ability to submit your tracks to Repost',
            'slug' => 'submit_repost'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Ability to submit your tracks to STM Releases',
            'description'    => 'Ability to submit your tracks to STM Releases',
            'slug' => 'submit_stm_releases'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Advertise one of your tracks on your profile through label release promo box',
            'description'    => 'Advertise one of your tracks on your profile through label release promo box',
            'slug' => 'label_release'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => 'Chance for Discover submissions to receive a spotlight position',
            'description'    => 'Chance for Discover submissions to receive a spotlight position',
            'slug' => 'spotlight_position'
        ));
        SubscriptionFeature::firstOrCreate(array(
            'name' => "Chance for your track to be 'suggested' on a landing page for any track",
            'description'    => "Chance for your track to be 'suggested' on a landing page for any track",
            'slug' => 'suggested_on'
        ));


        // Plan = free 
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  1,
            'feature_id' =>  2
        ));

        // Plan = infinite 
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  3,
            'feature_id' =>  1
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  3,
            'feature_id' => 2
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  3,
            'feature_id' => 3
        ));

        // Plan = Gating & Discover 
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' =>  1
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' => 2
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' => 3
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' => 4
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' => 5
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' => 6
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' => 8
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  4,
            'feature_id' => 9
        ));

        // Plan = Gating, Discover & Releases
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' =>  1
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 2
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 3
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 4
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 5
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 6
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 7
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 8
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 9
        ));
        PlanFeature::firstOrCreate(array(
            'plan_id' =>  5,
            'feature_id' => 10
        ));



    }

}