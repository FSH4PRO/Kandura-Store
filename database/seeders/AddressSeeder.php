<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\City;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        $customers = Customer::all();
        $cities = City::pluck('id')->all();

        if ($customers->isEmpty()) {
            return;
        }

        if (empty($cities)) {
            return;
        }

        foreach ($customers as $customer) {

            // Don't duplicate addresses every time the seeder runs
            if ($customer->addresses()->exists()) {
                continue;
            }

            $numAddresses = rand(1, 3);

            for ($i = 0; $i < $numAddresses; $i++) {
                Address::create([
                    'customer_id' => $customer->id,

                    'city_id' => $cities[array_rand($cities)],

                    'street' => $faker->streetAddress,

                    'latitude' => $faker->latitude,

                    'longitude' => $faker->longitude,

                    'details' => $faker->optional()->sentence,
                ]);
            }
        }
    }
}