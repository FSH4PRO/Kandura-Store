<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // امسح الكاش القديم
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // -----------------------------
        // 1) تعريف الصلاحيات
        // -----------------------------

        // صلاحيات المستخدم (الزبون / customer)
        $userPermissions = [
            'create_profiles',
            'edit_profiles',
            'delete_profiles',

            'create_addresses',
            'edit_addresses',
            'delete_addresses',

            'create_measurements',
            'edit_measurements',
            'delete_measurements',

            'create_designs',
            'edit_designs',
            'delete_designs',
            'view_designs',

            'create_orders',
            'view_orders',

            'view_wallets_balances',
            'view_wallets_transactions',

            'create_reviews',
            'view_notifications',
        ];

        // صلاحيات الأدمن (لوحة التحكم)
        $adminPermissions = [
            'view_users',
            'disable_users',
            'delete_users',

            'change_order_status',

            'create_coupons',
            'edit_coupons',
            'delete_coupons',

            'create_design_options',
            'edit_design_options',
            'delete_design_options',

            'view_reviews',
            'control_reviews',

            'send_notifications',
            'add_wallets',
            'withdraw_balances',
        ];

        // صلاحيات السيستم / سوبر أدمن
        $systemPermissions = [
            'create_admins',
            'edit_admins',
            'delete_admins',
            'manage_system_settings',
            'view_reports',
            'add_roles',
            'edit_roles',
            'delete_roles',
        ];

        // -----------------------------
        // 2) إنشاء الـ Permissions حسب الـ guard
        // -----------------------------

        // صلاحيات الزبون على guard customer
        foreach ($userPermissions as $permName) {
            Permission::firstOrCreate([
                'name'       => $permName,
                'guard_name' => 'customer',
            ]);
        }

        // صلاحيات الأدمن على guard admin
        foreach (array_merge($adminPermissions, $systemPermissions) as $permName) {
            Permission::firstOrCreate([
                'name'       => $permName,
                'guard_name' => 'admin',
            ]);
        }

        // -----------------------------
        // 3) إنشاء الـ Roles
        // -----------------------------

        // دور user للزبون (customer guard)
        $userRole = Role::firstOrCreate([
            'name'       => 'user',
            'guard_name' => 'customer',
        ]);

        // دور admin للوحة التحكم (admin guard)
        $adminRole = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'admin',
        ]);

        // دور super_admin للوحة التحكم (admin guard)
        $superAdminRole = Role::firstOrCreate([
            'name'       => 'super_admin',
            'guard_name' => 'admin',
        ]);

        // -----------------------------
        // 4) ربط الصلاحيات بالأدوار
        // -----------------------------

        // user (الزبون) → كل صلاحيات customer
        $userRole->syncPermissions(
            Permission::whereIn('name', $userPermissions)
                ->where('guard_name', 'customer')
                ->get()
        );

        // admin → صلاحيات الأدمن فقط
        $adminRole->syncPermissions(
            Permission::whereIn('name', $adminPermissions)
                ->where('guard_name', 'admin')
                ->get()
        );

        // super_admin → كل صلاحيات admin (أدمن + سيستم)
        $superAdminRole->syncPermissions(
            Permission::where('guard_name', 'admin')->get()
        );

        // ريفرش للكاش بعد ما خلصنا
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
