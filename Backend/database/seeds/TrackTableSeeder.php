<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Track;

class TrackTableSeeder extends Seeder {

    public function run()
    {

        \DB::table('tracks')->truncate();

        $faker = Faker::create();
        $users = App\User::lists('id')->all();
        $type=array("discover","remix","soundcloud","music-video");
        foreach(range(1, 10) as $index) {

            Track::create([
                'track_name' => $faker->catchPhrase." [FREE DOWNLOAD]",
                'slug' => Str::slug("test".$index),
                'type' => $type[array_rand($type)],
                'file' => "http://api.soundcloud.com/apps/124",
                'user_id' => $faker->randomElement($users),
                'cover_image' => ""


                // 'soundcloud_url' => "https://soundcloud.com/sanamofficial/sanam-gulabi-aankhen",
                
                // 'username' => $faker->name,
                // 'user_uri' => "http://api.soundcloud.com/users/3699101",
                // 'user_permalink_url' => "http://soundcloud.com/user2835985",
                // 'app_name' => "SoundCloud iPhone",
                // 'app_uri' => "http://api.soundcloud.com/apps/124",
                // 'app_permalink_url' => "http://soundcloud.com/apps/iphone",
                // 'permalink_url' => "http://soundcloud.com/apps/iphone",
                // 'attachments_uri' => "http://soundcloud.com/apps/iphone",
                // 'duration' => $faker->time,
                
                // 'format' => $faker->word,
                // 'user_id' => $faker->randomElement($users),
                // 'description' => $faker->text
            ]);
        }

    }

}