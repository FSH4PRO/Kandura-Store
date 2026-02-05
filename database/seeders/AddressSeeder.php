<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('ar_SA'); // أو 'en_US' حسب الحاجة

        $customers = Customer::all();

        foreach ($customers as $customer) {
            // لكل customer نعمل بين 1 و 3 عناوين
            $numAddresses = rand(1, 3);

            for ($i = 0; $i < $numAddresses; $i++) {
                Address::create([
                    'customer_id' => $customer->id,
                    'city_id' => rand(1, 12), // Assuming there are 12 cities in the database
                    'street' => $faker->streetAddress,
                    'latitude' => $faker->latitude,
                    'longitude' => $faker->longitude,
                    'details' => $faker->optional()->sentence,
                ]);
            }
        }
    }
}
