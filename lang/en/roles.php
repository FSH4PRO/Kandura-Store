<?php

return [

  // Roles index page
  'index' => [
    'title'      => 'Roles',
    'heading'    => 'Manage Roles',
    'subheading' => 'View and manage all roles in the admin panel.',
    'search_placeholder' => 'Search all columns...',
    'search_button' => 'Search',
    'reset_button' => 'Reset',

    'create_button' => 'Create New Role',

    'filters' => [
      'search_label'       => 'Search',
      'search_placeholder' => 'Search all columns...',
      'submit'             => 'Filter',
      'reset'              => 'Reset',
    ],

    'table' => [
      'id'                => 'ID',
      'name'              => 'Role Name',
      'permissions_count' => 'Permissions',
      'created_at'        => 'Created At',
      'actions'           => 'Actions',
      'empty'             => 'No roles found.',
    ],

    'actions' => [
      'edit'           => 'Edit',
      'delete'         => 'Delete',
      'confirm_delete' => 'Are you sure you want to delete this role?',
    ],
  ],

  // Create role page
  'create' => [
    'title'        => 'Create Role',
    'heading'      => 'Create New Role',
    'subheading'   => 'Create a new role and assign permissions to it.',
    'back_to_list' => 'Back to roles list',

    'form' => [
      'name_label'       => 'Role system name *',
      'name_placeholder' => 'Example: manage_users, manage_orders',
      'name_help'        => 'This name will be used to identify this role in the admin panel.',
      'permissions'      => 'Permissions',
      'no_permissions'   => 'No permissions found.',
      'permissions_help' => 'Select the permissions you want to attach to this role.',
      'permissions_label' => 'Permissions',
      'submit'           => 'Save Role',
    ],
  ],

  // Edit role page
  'edit' => [
    'title'        => 'Edit Role',
    'heading'      => 'Edit Role',
    'subheading'   => 'Update the role name and its permissions.',
    'back_to_list' => 'Back to roles list',

    'form' => [
      'name_label'       => 'Role system name *',
      'name_placeholder' => 'Example: manage_users, manage_orders',
      'permissions'      => 'Permissions',
      'permissions_help' => 'Update the permissions attached to this role.',
      'submit'           => 'Update Role',
    ],
  ],

  // Flash / status messages
  'messages' => [
    'created'       => 'Role created successfully.',
    'updated'       => 'Role updated successfully.',
    'deleted'       => 'Role deleted successfully.',
    'not_deletable' => 'This role cannot be deleted.',
  ],

  'permission_groups' => [
    'users'          => 'Users',
    'admins'         => 'Admins',
    'addresses'      => 'Addresses',
    'designs_options'=> 'Design Options',
    'designs'        => 'Designs',
    'orders'         => 'Orders',
    'invoices'       => 'Invoices',
    'wallets'        => 'Wallets',
    'notifications'  => 'Notifications',
    'system'         => 'System',
    'dashboard'      => 'Dashboard',
    'roles'          => 'Roles',
    'coupons'        => 'Coupons',
    'reviews'        => 'Reviews',
    'design_options' => 'Design Options',
  ],
];
