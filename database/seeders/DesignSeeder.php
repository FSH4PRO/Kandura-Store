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

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $customer = Customer::first();

            if (! $customer) {
                $customer = Customer::create([
                    'phone' => '0500000000',
                    'password' => 'password',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            if (! $customer->user) {
                User::create([
                    'name' => [
                        'en' => 'Customer User',
                        'ar' => 'مستخدم العميل',
                    ],

                    'is_active' => true,

                    'usable_id' => $customer->id,

                    'usable_type' => Customer::class,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Sizes
            |--------------------------------------------------------------------------
            */

            $sizesData = [
                [
                    'code' => 'XS',
                    'name' => [
                        'en' => 'Extra Small',
                        'ar' => 'صغير جداً',
                    ],
                    'sort_order' => 1,
                ],
                [
                    'code' => 'S',
                    'name' => [
                        'en' => 'Small',
                        'ar' => 'صغير',
                    ],
                    'sort_order' => 2,
                ],
                [
                    'code' => 'M',
                    'name' => [
                        'en' => 'Medium',
                        'ar' => 'متوسط',
                    ],
                    'sort_order' => 3,
                ],
                [
                    'code' => 'L',
                    'name' => [
                        'en' => 'Large',
                        'ar' => 'كبير',
                    ],
                    'sort_order' => 4,
                ],
                [
                    'code' => 'XL',
                    'name' => [
                        'en' => 'X-Large',
                        'ar' => 'كبير جداً',
                    ],
                    'sort_order' => 5,
                ],
                [
                    'code' => 'XXL',
                    'name' => [
                        'en' => 'XX-Large',
                        'ar' => 'ضخم',
                    ],
                    'sort_order' => 6,
                ],
            ];

            foreach ($sizesData as $size) {
                Size::updateOrCreate(
                    [
                        'code' => $size['code'],
                    ],
                    [
                        'name' => $size['name'],
                        'sort_order' => $size['sort_order'],
                    ]
                );
            }

            $allSizes = Size::orderBy('sort_order')->get();

            /*
            |--------------------------------------------------------------------------
            | Design Options
            |--------------------------------------------------------------------------
            */

            $options = DesignOption::all();
            /*
            |--------------------------------------------------------------------------
            | Designs
            |--------------------------------------------------------------------------
            */

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
                        'color' => ['White'],
                        'fabric_type' => ['Cotton'],
                        'sleeve_type' => ['Long Sleeve'],
                        'dome_type' => ['Classic Button'],
                    ],

                    'image_urls' => [
                        'https://images.pexels.com/photos/5416825/pexels-photo-5416825.jpeg',
                        'https://images.pexels.com/photos/5416864/pexels-photo-5416864.jpeg',
                        'https://images.pexels.com/photos/5416863/pexels-photo-5416863.jpeg',
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
                        'color' => ['Black'],
                        'fabric_type' => ['Linen'],
                        'sleeve_type' => ['Long Sleeve'],
                        'dome_type' => ['Hidden Button'],
                    ],

                    'image_urls' => [
                        'https://images.pexels.com/photos/30627673/pexels-photo-30627673.jpeg',
                        'https://images.pexels.com/photos/34171661/pexels-photo-34171661.jpeg',
                        'https://images.pexels.com/photos/28977571/pexels-photo-28977571.jpeg',
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
                        'color' => ['White'],
                        'fabric_type' => ['Cotton'],
                        'sleeve_type' => ['Short Sleeve'],
                        'dome_type' => ['Classic Button'],
                    ],

                    'image_urls' => [
                        'https://images.pexels.com/photos/31229196/pexels-photo-31229196.jpeg',
                        'https://images.pexels.com/photos/5416858/pexels-photo-5416858.jpeg',
                        'https://images.pexels.com/photos/5416746/pexels-photo-5416746.jpeg',
                    ],
                ],
            ];

            foreach ($designsData as $row) {

                /*
                |--------------------------------------------------------------------------
                | Don't duplicate design
                |--------------------------------------------------------------------------
                */
                $design = Design::updateOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'name->en' => $row['name']['en'],
                    ],
                    [
                        'description' => $row['description'],
                        'price' => $row['price'],
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Sizes
                |--------------------------------------------------------------------------
                */

                $sizeIds = $allSizes
                    ->whereIn('code', $row['sizes_codes'])
                    ->pluck('id')
                    ->all();

                $design->sizes()->sync($sizeIds);

                /*
                |--------------------------------------------------------------------------
                | Options
                |--------------------------------------------------------------------------
                */

                $this->syncDesignOptionsByNames(
                    $design,
                    $options,
                    $row['option_types']
                );

                /*
                |--------------------------------------------------------------------------
                | Images
                |--------------------------------------------------------------------------
                */

                // Only add images if the design has no images.
                if (! $design->getMedia('images')->count()) {

                    foreach ($row['image_urls'] as $url) {

                        try {
                            $design
                                ->addMediaFromUrl($url)
                                ->toMediaCollection('images');

                        } catch (\Throwable $e) {
                            // Don't break the entire seeder if an image fails.
                        }
                    }
                }
            }
        });
    }

    protected function syncDesignOptionsByNames(
        Design $design,
        $allOptions,
        array $optionTypes
    ): void {

        $design->optionSelections()->delete();

        $rows = [];

        foreach ($optionTypes as $type => $namesEn) {

            foreach ($namesEn as $nameEn) {

                $option = $allOptions
                    ->where('type', $type)
                    ->first(function (DesignOption $opt) use ($nameEn) {

                        $optName = $opt->getTranslation(
                            'name',
                            'en'
                        );

                        return strtolower($optName) === strtolower($nameEn);
                    });

                if (! $option) {
                    continue;
                }

                $rows[] = [
                    'design_id' => $design->id,
                    'design_option_id' => $option->id,
                    'value' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($rows)) {
            DesignOptionSelection::insert($rows);
        }
    }
}