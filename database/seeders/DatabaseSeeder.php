<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            CitySeeder::class,
            // AddressSeeder::class,
            SizeSeeder::class,
            DesignOptionsSeeder::class,
            // DesignSeeder::class,
        ]);
    }
}
