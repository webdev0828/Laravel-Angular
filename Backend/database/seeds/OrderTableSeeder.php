<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Order;
use App\Transaction;

class OrderTableSeeder extends Seeder {

    public function run()
    {
        \DB::table('orders')->delete();
        \DB::table('transactions')->delete();

        $faker = Faker::create();
       
        foreach(range(1, 15) as $index) {

        $order = Order::create(array(
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email'    => $faker->email,
                'phone' => $faker->phoneNumber,
                'user_id' => $faker->randomDigit,
                'transaction_id' => $faker->randomNumber,
                'subscription' => 'PREMIUM',
                'payment_status' => 'success',
                'order_status' => 'completed'
            ));

            Transaction::create([
                'order_id' => $order->id,
                'amount' => $faker->randomFloat,
                'track_id' => $faker->randomDigit
            ]);
        }
    }

}