<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name'              => ['en' => 'Super Admin', 'ar' => 'المشرف العام'],
            'email'             => 'superadmin@gmail.com',
            'password'          => bcrypt('12345678'),
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $admin = User::create([
            'name'              => ['en' => 'Admin', 'ar' => 'المسؤول'],
            'email'             => 'admin@gmail.com',
            'password'          => bcrypt('12345678'),
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name'              => ['en' => 'User', 'ar' => 'المستخدم'],
            'email'             => 'user@gmail.com',
            'password'          => bcrypt('12345678'),
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        if ($superAdminRole && $adminRole && $userRole) {
            $superAdmin->assignRole($superAdminRole);
            $admin->assignRole($adminRole);
            $user->assignRole($userRole);
        }
    }
}
