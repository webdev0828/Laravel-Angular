<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\News;

class NewsTrackTableSeeder extends Seeder {

    public function run()
    {

       // \DB::table('news')->delete();

        $faker = Faker::create();
        $users = App\User::lists('id')->all();
        $newsCategory = App\NewsCategory::lists('id')->all();
        
        foreach(range(1, 10) as $index) {

            News::create([
                'title' => $faker->word,
                'user_id' => $faker->randomElement($users),
                'category_id' => $faker->randomElement($newsCategory),
                'description' => $faker->paragraph,
                'tags' => "pop, Folk",
                'image_name' => ''
            ]);
        }

    }

}