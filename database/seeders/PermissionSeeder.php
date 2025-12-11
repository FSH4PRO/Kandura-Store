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
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // ---------------------------------------------------
        // 1) Permissions
        // ---------------------------------------------------

        // Users management
        $permissions_users = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
        ];

        $permissions_admins = [
            'admins.view',
            'admins.create',
            'admins.edit',
            'admins.delete',
        ];

        // Addresses management
        $permissions_addresses = [
            'addresses.view',
            'addresses.create',
            'addresses.edit',
            'addresses.delete',
        ];

        $permissions_designs_options = [
            'design_options.view',
            'design_options.create',
            'design_options.edit',
            'design_options.delete',
        ];

        // Designs management
        $permissions_designs = [
            'designs.view',
            'designs.create',
            'designs.edit',
            'designs.delete',
        ];

        // Orders management
        $permissions_orders = [
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.change_status',
        ];

        // Wallets
        $permissions_wallets = [
            'wallets.view',
            'wallets.add',
            'wallets.withdraw',
        ];

        // Notifications
        $permissions_notifications = [
            'notifications.view',
            'notifications.send',
        ];

        // System settings
        $permissions_system = [
            'system.view_reports',
            'system.manage_settings',
        ];

        $permissions_dashboard = [
            'dashboard.access',
        ];

        $permissions_roles = [
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
        ];

        $allPermissions = array_merge(
            $permissions_users,
            $permissions_admins,
            $permissions_addresses,
            $permissions_designs_options,
            $permissions_designs,
            $permissions_orders,
            $permissions_wallets,
            $permissions_notifications,
            $permissions_system,
            $permissions_dashboard,
            $permissions_roles
        );

        // ---------------------------------------------------
        // 2) إنشاء الـ Permissions (guard = user)
        // ---------------------------------------------------

        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate([
                'name'       => $perm,
                'guard_name' => 'admin',
            ]);
        }

        // ---------------------------------------------------
        // 3) إنشاء Micro Roles
        // ---------------------------------------------------

        // manage_users
        $role_manage_users = Role::firstOrCreate([
            'name'       => 'manage_users',
            'guard_name' => 'admin',
        ]);
        $role_manage_users->syncPermissions($permissions_users);

        // manage_admins
        $role_manage_admins = Role::firstOrCreate([
            'name'       => 'manage_admins',
            'guard_name' => 'admin',
        ]);
        $role_manage_admins->syncPermissions($permissions_admins);

        // manage_addresses
        $role_manage_addresses = Role::firstOrCreate([
            'name'       => 'manage_addresses',
            'guard_name' => 'admin',
        ]);

        //manage design options
        $role_manage_design_options = Role::firstOrCreate([
            'name'       => 'manage_design_options',
            'guard_name' => 'admin',
        ]);
        $role_manage_design_options->syncPermissions($permissions_designs_options);

        $role_manage_addresses->syncPermissions($permissions_addresses);

        // manage_designs
        $role_manage_designs = Role::firstOrCreate([
            'name'       => 'manage_designs',
            'guard_name' => 'admin',
        ]);
        $role_manage_designs->syncPermissions($permissions_designs);

        // manage_orders
        $role_manage_orders = Role::firstOrCreate([
            'name'       => 'manage_orders',
            'guard_name' => 'admin',
        ]);
        $role_manage_orders->syncPermissions($permissions_orders);

        // manage_wallets
        $role_manage_wallets = Role::firstOrCreate([
            'name'       => 'manage_wallets',
            'guard_name' => 'admin',
        ]);
        $role_manage_wallets->syncPermissions($permissions_wallets);

        // manage_notifications
        $role_manage_notifications = Role::firstOrCreate([
            'name'       => 'manage_notifications',
            'guard_name' => 'admin',
        ]);
        $role_manage_notifications->syncPermissions($permissions_notifications);

        // manage_system
        $role_manage_system = Role::firstOrCreate([
            'name'       => 'manage_system',
            'guard_name' => 'admin',
        ]);
        $role_manage_system->syncPermissions($permissions_system);

        $role_mange_dashboard = Role::firstOrCreate([
            'name'       => 'dashboard_access',
            'guard_name' => 'admin',
        ]);
        $role_mange_dashboard->syncPermissions($permissions_dashboard);

        $role_manage_roles = Role::firstOrCreate([
            'name'       => 'manage_roles',
            'guard_name' => 'admin',
        ]);
        $role_manage_roles->syncPermissions($permissions_roles);

        // ---------------------------------------------------
        // 4) SUPER ADMIN ROLE (يجمع كل شيء)
        // ---------------------------------------------------

        $superAdminRole = Role::firstOrCreate([
            'name'       => 'super_admin',
            'guard_name' => 'admin',
        ]);

        $superAdminRole->syncPermissions($allPermissions);

        // ---------------------------------------------------
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
