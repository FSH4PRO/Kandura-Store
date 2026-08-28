<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $super = Admin::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'password' => Hash::make('12345678'),
                'super_admin' => true,
            ]
        );

        $super->user()->updateOrCreate(
            [],
            [
                'name' => [
                    'en' => 'Super Admin',
                    'ar' => 'مشرف عام',
                ],
                'is_active' => true,
            ]
        );

        $superAdminRole = Role::where('name', 'super_admin')
            ->where('guard_name', 'admin')
            ->first();

        if ($superAdminRole) {
            $super->syncRoles([$superAdminRole]);
        }


        /*
        |--------------------------------------------------------------------------
        | MAIN ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = Admin::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'password' => Hash::make('12345678'),
                'super_admin' => false,
            ]
        );

        $admin->user()->updateOrCreate(
            [],
            [
                'name' => [
                    'en' => 'Main Admin',
                    'ar' => 'مسؤول النظام',
                ],
                'is_active' => true,
            ]
        );

        $admin->syncRoles([
            'manage_users',
            'manage_addresses',
            'manage_orders',
            'manage_designs',
            'manage_design_options',
            'dashboard_access',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADDITIONAL ADMINS
        |--------------------------------------------------------------------------
        */

        $additionalAdmins = [
            [
                'email' => 'salesadmin@gmail.com',
                'name' => [
                    'en' => 'Sales Admin',
                    'ar' => 'مسؤول المبيعات',
                ],
                'roles' => [
                    'manage_orders',
                    'dashboard_access',
                ],
            ],

            [
                'email' => 'designadmin@gmail.com',
                'name' => [
                    'en' => 'Design Admin',
                    'ar' => 'مسؤول التصاميم',
                ],
                'roles' => [
                    'manage_designs',
                    'manage_design_options',
                ],
            ],

            [
                'email' => 'useradmin@gmail.com',
                'name' => [
                    'en' => 'User Admin',
                    'ar' => 'مسؤول المستخدمين',
                ],
                'roles' => [
                    'manage_users',
                    'dashboard_access',
                ],
            ],
        ];

        foreach ($additionalAdmins as $data) {

            $admin = Admin::updateOrCreate(
                [
                    'email' => $data['email'],
                ],
                [
                    'password' => Hash::make('12345678'),
                    'super_admin' => false,
                ]
            );

            $admin->user()->updateOrCreate(
                [],
                [
                    'name' => $data['name'],
                    'is_active' => true,
                ]
            );

            $admin->syncRoles($data['roles']);
        }
        /*
        |--------------------------------------------------------------------------
        | MAIN CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customer = Customer::updateOrCreate(
            [
                'phone' => '0911111111',
            ],
            [
                'password' => Hash::make('12345678'),
            ]
        );

        $customer->user()->updateOrCreate(
            [],
            [
                'name' => [
                    'en' => 'Customer User',
                    'ar' => 'مستخدم زبون',
                ],
                'is_active' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DEMO CUSTOMERS
        |--------------------------------------------------------------------------
        */

        $demoCustomers = [
            [
                'phone' => '0911111112',
                'name' => [
                    'en' => 'John Doe',
                    'ar' => 'جون دو',
                ],
            ],

            [
                'phone' => '0911111113',
                'name' => [
                    'en' => 'Jane Smith',
                    'ar' => 'جين سميث',
                ],
            ],

            [
                'phone' => '0911111114',
                'name' => [
                    'en' => 'Ali Hassan',
                    'ar' => 'علي حسن',
                ],
            ],

            [
                'phone' => '0911111115',
                'name' => [
                    'en' => 'Fatima Noor',
                    'ar' => 'فاطمة نور',
                ],
            ],
        ];

        foreach ($demoCustomers as $data) {

            $customer = Customer::updateOrCreate(
                [
                    'phone' => $data['phone'],
                ],
                [
                    'password' => Hash::make('12345678'),
                ]
            );

            $customer->user()->updateOrCreate(
                [],
                [
                    'name' => $data['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
