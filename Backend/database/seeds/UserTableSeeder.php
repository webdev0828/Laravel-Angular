<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\User;
use App\AdminUser;
use App\ArtistsProfile;


class UserTableSeeder extends Seeder {

    public function run()
    {
        // use the factory to create a Faker\Generator instance

       \DB::table('users')->truncate();

        $user = User::firstOrCreate([
                                        'name' => 'Admin',
                                        'email'    => 'admin@stm.com',
                                        'user_type'    => 'admin',
                                        'password' => \Hash::make('12345'),
                                        'status' => '1',
                                        'slug'=>'admin'

                                    ]);

        $adminUserProfile = AdminUser::firstOrCreate([
                                                        'user_id'   => $user->id, 
                                                        'fname'     => 'Admin',
                                                        'lname'     => '',
                                                        'name'      => 'Admin',
                                                    ]);

        $artist = User::firstOrCreate([
                                        'name' => 'Stm Artist',
                                        'email'    => 'artist@stm.com',
                                        'user_type'    => 'artist',
                                        'password' => \Hash::make('12345'),
                                        'status' => '1',
                                        'isStmArtist' => '0',
                                        'slug'=>'stm-artist'

                                    ]);


        $artistUserProfile = ArtistsProfile::firstOrCreate([
                                                        'user_id'   => $artist->id, 
                                                        'first_name'     => 'Stm Artist',
                                                        'last_name'     => '',
                                                        'name'      => 'Stm Artist',
                                                    ]);

        // $this->command->info(' Email is admin@sorethumbmedia.com and password is 12345');
        
        // $faker = Faker::create();
        
        // foreach(range(1, 15) as $index) {

        //     User::create([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'phone' => '3455558645',
        //         // 'password' => $faker->word,
        //         'password' => \Hash::make('12345'),
        //         'status' => $faker->boolean($chanceOfGettingTrue = 90),
        //         'user_type' => 'stm_user'
        //     ]);
        // }

        // foreach(range(1, 15) as $index) {

        //     User::create([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'phone' => '3455558645',
        //         'password' => \Hash::make('12345'),
        //         'status' => $faker->boolean($chanceOfGettingTrue = 90),
        //         'user_type' => 'admin_user'
        //     ]);
        // }

        // foreach(range(1, 5) as $index) {

        //     User::create([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'phone' => '3455558645',
        //         'subscription' => 'FREE',
        //         'password' => \Hash::make('12345'),
        //         'status' => $faker->boolean($chanceOfGettingTrue = 90),
        //         'user_type' => 'artist'
        //     ]);
        // }

        // foreach(range(1, 5) as $index) {

        //     User::create([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'phone' => '3455558645',
        //         'subscription' => 'PREMIUM',
        //         'password' => \Hash::make('12345'),
        //         'status' => $faker->boolean($chanceOfGettingTrue = 90),
        //         'user_type' => 'artist'
        //     ]);
        // }

        // foreach(range(1, 5) as $index) {

        //     User::create([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'phone' => '3455558645',
        //         'subscription' => 'UNLIMITED',
        //         'password' => \Hash::make('12345'),
        //         'status' => $faker->boolean($chanceOfGettingTrue = 90),
        //         'user_type' => 'artist'
        //     ]);
        // }

        // foreach(range(1, 5) as $index) {

        //     User::create([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'phone' => '3455558645',
        //         'subscription' => 'PROPREMIUM',
        //         'password' => \Hash::make('12345'),
        //         'status' => $faker->boolean($chanceOfGettingTrue = 90),
        //         'user_type' => 'artist'
        //     ]);
        // }

        // foreach(range(1, 5) as $index) {

        //     User::create([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'phone' => '3455558645',
        //         'subscription' => 'PROUNLIMITED',
        //         'password' => \Hash::make('12345'),
        //         'status' => $faker->boolean($chanceOfGettingTrue = 90),
        //         'user_type' => 'artist'
        //     ]);
        // }

    }

}