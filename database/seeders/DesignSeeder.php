<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Design;
use App\Models\DesignOption;
use App\Models\DesignOptionSelection;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            
            $customer = Customer::first();

            if (! $customer) {
                
                $customer = Customer::create([
                    'phone'    => '0500000000',
                    'password' => 'password', 
                ]);
            }

           
            if (! $customer->user) {
                User::create([
                    'name'        => [
                        'en' => 'Customer User',
                        'ar' => 'مستخدم العميل',
                    ],
                    'is_active'   => true,
                    'usable_id'   => $customer->id,
                    'usable_type' => Customer::class,
                ]);
            }

            
            if (Size::count() === 0) {
                $sizesData = [
                    ['code' => 'XS',  'name' => ['en' => 'Extra Small', 'ar' => 'صغير جداً'], 'sort_order' => 1],
                    ['code' => 'S',   'name' => ['en' => 'Small',       'ar' => 'صغير'],        'sort_order' => 2],
                    ['code' => 'M',   'name' => ['en' => 'Medium',      'ar' => 'متوسط'],      'sort_order' => 3],
                    ['code' => 'L',   'name' => ['en' => 'Large',       'ar' => 'كبير'],        'sort_order' => 4],
                    ['code' => 'XL',  'name' => ['en' => 'X-Large',     'ar' => 'كبير جداً'],  'sort_order' => 5],
                    ['code' => 'XXL', 'name' => ['en' => 'XX-Large',    'ar' => 'ضخم'],        'sort_order' => 6],
                ];

                foreach ($sizesData as $row) {
                    Size::create($row);
                }
            }

            $allSizes = Size::orderBy('sort_order')->get();

            
            if (DesignOption::count() === 0) {
                $optionsSeed = [
                    [
                        'type' => 'color',
                        'name' => ['en' => 'White', 'ar' => 'أبيض'],
                    ],
                    [
                        'type' => 'color',
                        'name' => ['en' => 'Black', 'ar' => 'أسود'],
                    ],
                    [
                        'type' => 'fabric_type',
                        'name' => ['en' => 'Cotton', 'ar' => 'قطن'],
                    ],
                    [
                        'type' => 'fabric_type',
                        'name' => ['en' => 'Linen', 'ar' => 'كتان'],
                    ],
                    [
                        'type' => 'sleeve_type',
                        'name' => ['en' => 'Short Sleeve', 'ar' => 'نصف كم'],
                    ],
                    [
                        'type' => 'sleeve_type',
                        'name' => ['en' => 'Long Sleeve', 'ar' => 'كم طويل'],
                    ],
                    [
                        'type' => 'dome_type',
                        'name' => ['en' => 'Classic Button', 'ar' => 'زر كلاسيك'],
                    ],
                    [
                        'type' => 'dome_type',
                        'name' => ['en' => 'Hidden Button', 'ar' => 'زر مخفي'],
                    ],
                ];

                foreach ($optionsSeed as $row) {
                    DesignOption::create([
                        'name'      => $row['name'],
                        'type'      => $row['type'],
                        'is_active' => true,
                    ]);
                }
            }

            $options = DesignOption::all();
            DesignOptionSelection::query()->delete();
            Design::query()->delete();

           
            $designsData = [
                [
                    'name' => [
                        'en' => 'Classic White Kandura',
                        'ar' => 'كندورة بيضاء كلاسيكية',
                    ],
                    'description' => [
                        'en' => 'A simple and elegant white kandura suitable for daily wear.',
                        'ar' => 'كندورة بيضاء بسيطة وأنيقة مناسبة للاستخدام اليومي.',
                    ],
                    'price' => 10.00,
                    'sizes_codes' => ['XS', 'S'],
                    'option_types' => [
                        'color'       => ['White'],
                        'fabric_type' => ['Cotton'],
                        'sleeve_type' => ['Long Sleeve'],
                        'dome_type'   => ['Classic Button'],
                    ],
                    'image_urls' => [
                        'https://images.pexels.com/photos/3755706/pexels-photo-3755706.jpeg',
                    ],
                ],
                [
                    'name' => [
                        'en' => 'Black Premium Kandura',
                        'ar' => 'كندورة سوداء فاخرة',
                    ],
                    'description' => [
                        'en' => 'Premium black kandura with high-quality fabric.',
                        'ar' => 'كندورة سوداء فاخرة بقماش عالي الجودة.',
                    ],
                    'price' => 25.50,
                    'sizes_codes' => ['M', 'L', 'XL'],
                    'option_types' => [
                        'color'       => ['Black'],
                        'fabric_type' => ['Linen'],
                        'sleeve_type' => ['Long Sleeve'],
                        'dome_type'   => ['Hidden Button'],
                    ],
                    'image_urls' => [
                        'https://images.pexels.com/photos/6311656/pexels-photo-6311656.jpeg',
                    ],
                ],
                [
                    'name' => [
                        'en' => 'Summer Light Kandura',
                        'ar' => 'كندورة صيفية خفيفة',
                    ],
                    'description' => [
                        'en' => 'Lightweight kandura ideal for hot weather.',
                        'ar' => 'كندورة خفيفة مثالية لأيام الصيف الحارة.',
                    ],
                    'price' => 18.75,
                    'sizes_codes' => ['S', 'M'],
                    'option_types' => [
                        'color'       => ['White'],
                        'fabric_type' => ['Cotton'],
                        'sleeve_type' => ['Short Sleeve'],
                        'dome_type'   => ['Classic Button'],
                    ],
                    'image_urls' => [
                        'https://images.pexels.com/photos/8386964/pexels-photo-8386964.jpeg',
                    ],
                ],
            ];

            foreach ($designsData as $row) {
               
                $design = Design::create([
                    'customer_id' => $customer->id,
                    'name'        => $row['name'],
                    'description' => $row['description'],
                    'price'       => $row['price'],
                ]);

               
                $sizeIds = $allSizes
                    ->whereIn('code', $row['sizes_codes'])
                    ->pluck('id')
                    ->all();

                $design->sizes()->sync($sizeIds);

                
                $this->syncDesignOptionsByNames($design, $options, $row['option_types']);
               
                foreach ($row['image_urls'] as $url) {
                    try {
                        $design->addMediaFromUrl($url)
                            ->toMediaCollection('images');
                    } catch (\Throwable $e) {
                       
                    }
                }
            }
        });
    }

    
    protected function syncDesignOptionsByNames(Design $design, $allOptions, array $optionTypes): void
    {
       
        $design->optionSelections()->delete();

        $rows = [];

        foreach ($optionTypes as $type => $namesEn) {
            foreach ($namesEn as $nameEn) {
                $option = $allOptions
                    ->where('type', $type)
                    ->first(function (DesignOption $opt) use ($nameEn) {
                        $optName = $opt->getTranslation('name', 'en');
                        return strtolower($optName) === strtolower($nameEn);
                    });

                if ($option) {
                    $rows[] = [
                        'design_id'        => $design->id,
                        'design_option_id' => $option->id,
                        'value'            => null,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ];
                }
            }
        }

        if (! empty($rows)) {
            DesignOptionSelection::insert($rows);
        }
    }
}
