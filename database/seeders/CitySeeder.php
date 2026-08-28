<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['en' => 'Dubai', 'ar' => 'دبي'],
            ['en' => 'Abu Dhabi', 'ar' => 'أبو ظبي'],
            ['en' => 'Sharjah', 'ar' => 'الشارقة'],
            ['en' => 'Ajman', 'ar' => 'عجمان'],
            ['en' => 'Ras Al Khaimah', 'ar' => 'رأس الخيمة'],

            ['en' => 'Riyadh', 'ar' => 'الرياض'],
            ['en' => 'Jeddah', 'ar' => 'جدة'],
            ['en' => 'Dammam', 'ar' => 'الدمام'],

            ['en' => 'Doha', 'ar' => 'الدوحة'],

            ['en' => 'Kuwait City', 'ar' => 'مدينة الكويت'],

            ['en' => 'Manama', 'ar' => 'المنامة'],

            ['en' => 'Muscat', 'ar' => 'مسقط'],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                [
                    'name->en' => $city['en'],
                ],
                [
                    'name' => $city,
                ]
            );
        }
    }
}