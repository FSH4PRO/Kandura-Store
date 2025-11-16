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
        // clear cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // كل الصلاحيات حسب وثيقة المشروع تقريباً
        $permissions = [
            // user-level
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

            // admin-level
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

            // system-level (super admin)
            'create_admins',
            'edit_admins',
            'delete_admins',
            'manage_system_settings',
            'view_reports',
            'add_roles',
            'edit_roles',
            'delete_roles',
        ];

        // نعمل كل الصلاحيات على guard واحد: web
        foreach ($permissions as $permName) {
            Permission::firstOrCreate([
                'name'       => $permName,
                'guard_name' => 'web',
            ]);
        }

        // Roles
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // صلاحيات الـ user
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

        // صلاحيات الـ admin
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

        $userRole->syncPermissions(
            Permission::whereIn('name', $userPermissions)->get()
        );

        $adminRole->syncPermissions(
            Permission::whereIn('name', $adminPermissions)->get()
        );

        // super_admin بياخد كل شي
        $superAdminRole->syncPermissions(Permission::all());

        // refresh cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
