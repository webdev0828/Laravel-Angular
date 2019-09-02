<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\TrackGenres;

class TrackGenresTableSeeder extends Seeder {

    public function run()
    {

       // \DB::table('news')->delete();

        $faker = Faker::create();
        $tracks = App\Track::lists('id')->all();
        $genres = App\Genres::lists('id')->all();
        
        foreach(range(1, 10) as $index) {

            TrackGenres::create([
                'track_id' => $faker->randomElement($tracks),
                'genre_id' => $faker->randomElement($genres),
                'created_at' => $faker->date('Y-m-d'),
                'updated_at' => $faker->date('Y-m-d')
            ]);
        }

    }

}