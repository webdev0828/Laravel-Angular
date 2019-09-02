<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CompetitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        foreach(range(1, 10) as $index) {

            \App\Competition::create([
                'track_name' => $faker->word,
                'genres' => $faker->word,
                'name' => $faker->word,
                'start_date' => $faker->date('Y-m-d'),
                'end_date' => $faker->date('Y-m-d'),
                'announcement_date' => $faker->date('Y-m-d'),
                'winner_artist' => $faker->word,
                'description' => $faker->word,
                'slug' => $faker->word,
                'created_at' => $faker->date('Y-m-d'),
                'updated_at' => $faker->date('Y-m-d'),
            ]);
        }
    }
}

