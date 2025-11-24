<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Customer;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1) SUPER ADMIN  (Full System Rights)
        |--------------------------------------------------------------------------
        */

        $super = Admin::create([
            'email'    => 'superadmin@gmail.com',
            'password' => bcrypt('12345678'),
            'super_admin' => true,
        ]);

        // User مرتبط عبر polymorph
        $superUser = $super->user()->create([
            'name'      => ['en' => 'Super Admin', 'ar' => 'مشرف عام'],
            'is_active' => true,
        ]);

        // يعطى كل الأدوار
        $superUser->assignRole('super_admin');



        /*
        |--------------------------------------------------------------------------
        | 2) ADMIN with selective micro-roles
        |--------------------------------------------------------------------------
        */

        $admin = Admin::create([
            'email'    => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'super_admin' => false,
        ]);

        $adminUser = $admin->user()->create([
            'name'      => ['en' => 'Main Admin', 'ar' => 'مسؤول النظام'],
            'is_active' => true,
        ]);

        // تعطيه micro-roles حسب رغبتك
        $adminUser->assignRole([
            'manage_users',
            'manage_addresses',
            'manage_orders',
            'dashboard_access',
        ]);



        /*
        |--------------------------------------------------------------------------
        | 3) CUSTOMER (NO ROLES / PERMISSIONS)
        |--------------------------------------------------------------------------
        */

        $customer = Customer::create([
            'phone'    => '0911111111',
            'password' => bcrypt('12345678'),
        ]);

        $customerUser = $customer->user()->create([
            'name'      => ['en' => 'Customer User', 'ar' => 'مستخدم زبون'],
            'is_active' => true,
        ]);

        // لا نعطيه أي role أو permission نهائيًا
    }
}
