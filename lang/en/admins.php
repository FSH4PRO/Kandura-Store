<?php

return [
  'title'      => 'Admins',
  'heading'    => 'Admins Management',
  'subheading' => 'Here you can manage system administrators.',
  'create_button' => 'Create Admin',

  'create' => [
    'button' => 'Create Admin',
  ],

  'table' => [
    'id'         => 'ID',
    'name'       => 'Name',
    'email'      => 'Email',
    'roles'      => 'Roles',
    'status'     => 'Status',
    'created_at' => 'Created At',
    'actions'    => 'Actions',
    'empty'      => 'No admins found.',
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
    'dashboard_access' => 'Dashboard Access',
    'none'             => 'No roles',
  ],

  'actions' => [
    'delete'         => 'Delete',
    'confirm_delete' => 'Are you sure you want to delete this admin?',
    'no_actions'     => 'No actions available',
  ],

  'filters' => [
    'search_label'       => 'Search',
    'search_placeholder' => 'Search by name or email...',
    'status_label'       => 'Status',
    'status_all'         => 'All statuses',
    'status_active'      => 'Active',
    'status_inactive'    => 'Inactive',
    'role_label'         => 'Role',
    'role_all'           => 'All roles',
    'submit'             => 'Filter',
    'reset'              => 'Reset',
  ],
  'create' => [
    'title'        => 'Create Admin - Kandura Store',
    'heading'      => 'Create New Admin',
    'subheading'   => 'Create a new administrator account for the system.',
    'back_to_list' => 'Back to admins list',
  ],

  'form' => [
    'name_en'                => 'Name (English)',
    'name_en_placeholder'    => 'e.g. Admin User',
    'name_ar'                => 'Name (Arabic)',
    'name_ar_placeholder'    => 'e.g. System Manager',
    'email'                  => 'Email',
    'phone'                  => 'Phone number',
    'phone_placeholder'      => 'e.g. 0501234567',
    'password'               => 'Password',
    'password_confirmation'  => 'Password confirmation',
    'is_active'              => 'Account is active',
    'submit'                 => 'Create Admin',
    'roles'      => 'Roles (admin permissions)',
    'roles_help' => 'Select the permissions / roles you want to give to this admin. You can select multiple.',
  ],

  'messages' => [
    'created' => 'Admin created successfully.',
  ],

];
