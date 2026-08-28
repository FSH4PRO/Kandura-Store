<?php

return [
    'title'      => 'Users',
    'heading'    => 'Users Management',
    'subheading' => 'Search and filter users.',

    'filters' => [
        'search_label'       => 'Search',
        'search_placeholder' => 'Search all columns...',
        'status_label'       => 'Status',
        'status_all'         => 'All statuses',
        'status_active'      => 'Active',
        'status_inactive'    => 'Inactive',
        'role_label'         => 'Role',
        'role_all'           => 'All roles',
        'submit'             => 'Filter',
        'reset'              => 'Reset',
    ],

    'table' => [
        'id'         => 'ID',
        'profile_picture' => 'Profile Picture',
        'name'       => 'Name',
        'email'      => 'Email',
        'phone'      => 'Phone',
        'role'       => 'Role',
        'status'     => 'Status',
        'created_at' => 'Created At',
        'actions'    => 'Actions',
        'empty'      => 'No users yet.',
    ],

    'status_badge' => [
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ],

    'roles' => [
        'super_admin'      => 'Super Admin',
        'manage_users'     => 'Manage Users',
        'manage_admins'    => 'Manage Admins',
        'manage_addresses' => 'Manage Addresses',
        'manage_orders'    => 'Manage Orders',
        'user'             => 'User',
    ],

    'actions' => [
        'delete'         => 'Delete',
        'confirm_delete' => 'Are you sure you want to delete this user?',
        'no_actions'     => 'No actions available',
        'view_addresses' => 'View Addresses',
    ],

    'addresses' => [
        'title'         => 'User Addresses',
        'street'        => 'Street',
        'details'       => 'Details',
        'coordinates'   => 'Coordinates',
        'default'       => 'Default',
        'unknown_city'  => 'Unknown City',
        'no_addresses'  => 'This user has no addresses.',
        'error_loading' => 'Error loading addresses.',
    ],
];
