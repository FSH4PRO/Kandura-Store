<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            ['code' => 'XS', 'name' => ['en' => 'XS', 'ar' => 'XS'], 'sort_order' => 1],
            ['code' => 'S',  'name' => ['en' => 'S',  'ar' => 'S'],  'sort_order' => 2],
            ['code' => 'M',  'name' => ['en' => 'M',  'ar' => 'M'],  'sort_order' => 3],
            ['code' => 'L',  'name' => ['en' => 'L',  'ar' => 'L'],  'sort_order' => 4],
            ['code' => 'XL', 'name' => ['en' => 'XL', 'ar' => 'XL'], 'sort_order' => 5],
            ['code' => 'XXL', 'name' => ['en' => 'XXL', 'ar' => 'XXL'], 'sort_order' => 6],
        ];

        foreach ($sizes as $data) {
            Size::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
