<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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

        $superAdminRole = Role::where("name", 'super_admin')
            ->where("guard_name", 'admin')
            ->first();

        // يعطى كل الأدوار
        $super->assignRole($superAdminRole);



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
        $admin->assignRole([
            'manage_users',
            'manage_addresses',
            'manage_orders',
            'manage_designs',
            'manage_design_options',
            'dashboard_access',

        ]);

        /*
        |--------------------------------------------------------------------------
        | 3) ADDITIONAL ADMINS (for display / demo)
        |--------------------------------------------------------------------------
        */
        $additionalAdmins = [
            ['email' => 'salesadmin@gmail.com', 'name' => ['en' => 'Sales Admin', 'ar' => 'مسؤول المبيعات'], 'roles' => ['manage_orders', 'dashboard_access']],
            ['email' => 'designadmin@gmail.com', 'name' => ['en' => 'Design Admin', 'ar' => 'مسؤول التصاميم'], 'roles' => ['manage_designs', 'manage_design_options']],
            ['email' => 'useradmin@gmail.com', 'name' => ['en' => 'User Admin', 'ar' => 'مسؤول المستخدمين'], 'roles' => ['manage_users', 'dashboard_access']],
        ];

        foreach ($additionalAdmins as $data) {
            $a = Admin::create([
                'email' => $data['email'],
                'password' => bcrypt('12345678'),
                'super_admin' => false,
            ]);
            $u = $a->user()->create([
                'name' => $data['name'],
                'is_active' => true,
            ]);
            $a->assignRole($data['roles']);
        }



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


           /*
        |--------------------------------------------------------------------------
        | 4) CUSTOMERS (for display/demo)
        |--------------------------------------------------------------------------
        */
        $demoCustomers = [
            ['phone'=>'0911111112','name'=>['en'=>'John Doe','ar'=>'جون دو']],
            ['phone'=>'0911111113','name'=>['en'=>'Jane Smith','ar'=>'جين سميث']],
            ['phone'=>'0911111114','name'=>['en'=>'Ali Hassan','ar'=>'علي حسن']],
            ['phone'=>'0911111115','name'=>['en'=>'Fatima Noor','ar'=>'فاطمة نور']],
        ];

        foreach ($demoCustomers as $data) {
            $c = Customer::create([
                'phone' => $data['phone'],
                'password' => bcrypt('12345678'),
            ]);
            $u = $c->user()->create([
                'name' => $data['name'],
                'is_active' => true,
            ]);
            // لا نعطيه أي role أو permission
        }

       

    }
}
