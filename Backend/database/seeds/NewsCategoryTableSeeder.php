<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\NewsCategory;

class NewsCategoryTableSeeder extends Seeder {

    public function run()
    {
        // use the factory to create a Faker\Generator instance

        NewsCategory::firstOrCreate(array(
            'name' => 'Pop',
            'description'    => 'pop track and music related news'
        ));

        NewsCategory::firstOrCreate(array(
            'name' => 'Art song',
            'description'    => 'Art Video news'
        ));

        NewsCategory::firstOrCreate(array(
            'name' => 'Folk music',
            'description'    => 'Folk music news'
        ));

        NewsCategory::firstOrCreate(array(
            'name' => 'Vocal music',
            'description'    => 'Vocal music news'
        ));

        NewsCategory::firstOrCreate(array(
            'name' => 'Theme song',
            'description'    => 'Theme song news'
        ));

        NewsCategory::firstOrCreate(array(
            'name' => 'Blog',
            'description'    => 'Blog News'
        ));

    }

}