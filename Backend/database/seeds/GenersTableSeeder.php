<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Genres;

class GenersTableSeeder extends Seeder {

    public function run()
    {


        $genres = \App\Genres::all();
        foreach ($genres as $key => $value) {
            $slug = str_slug($value->name);
            $value->slug = $slug;
            $value->save();
        }

        // $faker = Faker::create();
        
        // foreach(range(1, 10) as $index) {

        //     Genres::create([
        //         'name' => $faker->word,
        //         'description' => $faker->paragraph
        //     ]);
        // }

    }

}