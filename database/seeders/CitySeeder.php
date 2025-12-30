<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // UAE
            ['en' => 'Dubai',        'ar' => 'دبي'],
            ['en' => 'Abu Dhabi',    'ar' => 'أبو ظبي'],
            ['en' => 'Sharjah',      'ar' => 'الشارقة'],
            ['en' => 'Ajman',        'ar' => 'عجمان'],
            ['en' => 'Ras Al Khaimah', 'ar' => 'رأس الخيمة'],

            // Saudi
            ['en' => 'Riyadh',       'ar' => 'الرياض'],
            ['en' => 'Jeddah',       'ar' => 'جدة'],
            ['en' => 'Dammam',       'ar' => 'الدمام'],

            // Qatar
            ['en' => 'Doha',         'ar' => 'الدوحة'],

            // Kuwait
            ['en' => 'Kuwait City',  'ar' => 'مدينة الكويت'],

            // Bahrain
            ['en' => 'Manama',       'ar' => 'المنامة'],

            // Oman
            ['en' => 'Muscat',       'ar' => 'مسقط'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name->en' => $city['en']], 
                ['name' => $city]            
            );
        }
    }
}
