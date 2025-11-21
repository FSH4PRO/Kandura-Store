<?php

return [

    'title'       => 'Users - Kandura Store',
    'heading'     => 'Users',
    'subheading'  => 'Manage system users (Users / Admins / Super Admins)',

    'filters' => [
        'search_label'  => 'Search',
        'search_placeholder' => 'Name, email, phone...',
        'status_label'  => 'Status',
        'status_all'    => 'All',
        'status_active' => 'Active',
        'status_inactive' => 'Inactive',
        'role_label'    => 'Role',
        'submit'        => 'Filter',
        'reset'         => 'Reset',
    ],

    'table' => [
        'id'        => '#',
        'name'      => 'Name',
        'email'     => 'Email',
        'phone'     => 'Phone',
        'role'      => 'Role',
        'status'    => 'Status',
        'created_at'=> 'Created at',
        'actions'   => 'Actions',
        'empty'     => 'No users match the current filters.',
    ],

    'status_badge' => [
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ],

    'actions' => [
        'no_actions'  => 'No actions available',
        'delete'      => 'Delete user',
        'confirm_del' => 'Are you sure you want to delete this user?',
    ],

    'create_admin' => [
        'button'   => 'Add admin',
        'title'    => 'Add new admin',
        'subtitle' => 'Create a new admin account.',
        'back'     => 'Back to users list',
        'success'  => 'Admin account created successfully.',
    ],

];
