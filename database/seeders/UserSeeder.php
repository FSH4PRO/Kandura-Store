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
        // 🟦 1) SUPER ADMIN
        $super = Admin::create([
            'email'     => 'superadmin@gmail.com',
            'password'  => bcrypt('12345678'),
            
        ]);

        $superUser = User::create([
            'name'      => ['en' => 'Super Admin', 'ar' => 'المشرف العام'],
            'is_active' => true,
            'usable_id'   => $super->id,
            'usable_type' => Admin::class,
        ]);

        // 🟥 تعيين دور سوبر أدمن
        $super->assignRole('super_admin');


        // 🟧 2) ADMIN
        $admin = Admin::create([
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('12345678'),
        ]);

        $adminUser = User::create([
            'name'      => ['en' => 'Admin', 'ar' => 'المسؤول'],
            'is_active' => true,
            'usable_id'   => $admin->id,
            'usable_type' => Admin::class,
        ]);

        // 🟨 تعيين دور أدمن
        $admin->assignRole('admin');


        // 🟩 3) CUSTOMER
        $customer = Customer::create([
             'phone'     => '0911111111',
             'password'  => bcrypt('12345678'),
        ]);

        $customerUser = User::create([
            'name'      => ['en' => 'User', 'ar' => 'المستخدم'],
            'is_active' => true,
            'usable_id'   => $customer->id,
            'usable_type' => Customer::class,
        ]);

        // 🟩 تعيين دور User (على customer guard)
        $customer->assignRole('user');
    }
}
