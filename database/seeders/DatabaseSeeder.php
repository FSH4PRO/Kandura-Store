<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,

            // Must run before AddressSeeder
            CitySeeder::class,

            SizeSeeder::class,
            DesignOptionsSeeder::class,

            // Depends on Customer + City + Size + DesignOptions
            AddressSeeder::class,
            // DesignSeeder::class,
        ]);
    }
}