<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Setting;

class SettingTableSeeder extends Seeder {

    public function run()
    {

        \DB::table('settings')->truncate();

        $type=array("discover","remix","soundcloud","music-video");


            Setting::create([
                'name' =>"dripfeed",
                'dripfeed_timer' => 10,
                'current_status' => 0,
                'last_checked_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        

    }

}