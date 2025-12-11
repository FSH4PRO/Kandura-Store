<?php

namespace Database\Seeders;

use App\Models\DesignOption;
use Illuminate\Database\Seeder;
use App\Enums\DesignOptionsType;

class DesignOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $options = [

            // ============ COLORS ============
            [
                'type' => DesignOptionsType::Color->value,
                'items' => [
                    ['en' => 'Black',    'ar' => 'أسود'],
                    ['en' => 'White',    'ar' => 'أبيض'],
                    ['en' => 'Blue',     'ar' => 'أزرق'],
                    ['en' => 'Red',      'ar' => 'أحمر'],
                    ['en' => 'Beige',    'ar' => 'بيج'],
                ],
            ],

            // ============ DOME TYPES ============
            [
                'type' => DesignOptionsType::DomeType->value,
                'items' => [
                    ['en' => 'Classic Dome', 'ar' => 'ياقة كلاسيكية'],
                    ['en' => 'Round Dome',   'ar' => 'ياقة دائرية'],
                    ['en' => 'Modern Dome',  'ar' => 'ياقة حديثة'],
                ],
            ],

            // ============ FABRIC TYPES ============
            [
                'type' => DesignOptionsType::FabricType->value,
                'items' => [
                    ['en' => 'Cotton',     'ar' => 'قطن'],
                    ['en' => 'Silk',       'ar' => 'حرير'],
                    ['en' => 'Linen',      'ar' => 'كتان'],
                    ['en' => 'Polyester',  'ar' => 'بوليستر'],
                ],
            ],

            // ============ SLEEVE TYPES ============
            [
                'type' => DesignOptionsType::SleeveType->value,
                'items' => [
                    ['en' => 'Short Sleeve',  'ar' => 'كم قصير'],
                    ['en' => 'Long Sleeve',   'ar' => 'كم طويل'],
                    ['en' => 'Wide Sleeve',   'ar' => 'كم واسع'],
                ],
            ],

        ];

        foreach ($options as $group) {

            foreach ($group['items'] as $item) {

                DesignOption::firstOrCreate([
                    'type' => $group['type'],
                    'name' => [
                        'en' => $item['en'],
                        'ar' => $item['ar'],
                    ],
                ], [
                    'is_active' => true,
                ]);
            }
        }
    }
}
