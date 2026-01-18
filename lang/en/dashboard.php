<?php

return [

  'title' => 'Dashboard - Kandura Store',

  'hero' => [
    'hello'    => 'Hello :name 👋',
    'subtitle' => 'Welcome to your Kandura Store dashboard. Here\'s what\'s happening today.',
    'default_admin_name' => 'Admin',
  ],

  'metrics' => [
    'total_orders' => 'Total Orders',
    'completed_orders' => 'Completed Orders',
    'total_users' => 'Total Users',
    'total_wallet_balance' => 'Wallet Balance',
    'this_month' => ':count this month',
    'of_total' => 'of total',
    'active' => 'active',
    'active_wallets' => 'active wallets',
  ],

  'charts' => [
    'user_growth' => 'User Growth',
    'order_growth' => 'Order Growth',
    'last_12_months' => 'Last 12 months',
  ],

  'recent_transactions' => [
    'title' => 'Recent Transactions',
    'no_transactions' => 'No recent transactions',
  ],

  'today_summary' => [
    'title' => 'Today\'s Summary',
    'orders' => 'Orders',
    'new_users' => 'New Users',
    'transactions' => 'Transactions',
    'pending_orders' => 'Pending Orders',
  ],

  'actions' => [
    'view_all' => 'View All',
    'view_transactions' => 'View Transactions',
  ],

  'transactions' => [
    'title' => 'Transactions',
    'filters' => [
      'title' => 'Filters',
      'type' => 'Type',
      'all_types' => 'All Types',
      'credit' => 'Credit',
      'debit' => 'Debit',
      'date_from' => 'Date From',
      'date_to' => 'Date To',
      'submit' => 'Apply Filters',
      'clear' => 'Clear Filters',
    ],
    'table' => [
      'customer' => 'Customer',
      'type' => 'Type',
      'amount' => 'Amount',
      'description' => 'Description',
      'date' => 'Date',
      'actions' => 'Actions',
      'view' => 'View',
    ],
    'no_data' => 'No transactions found',
  ],

  'stats' => [
    'total_users'      => 'Total users',
    'active_users'     => 'Active users',
    'total_admins'     => 'Admins / Supervisors',
    'total_addresses'  => 'Total addresses',
    'total_users_help' => 'Number of registered users in the system.',
    'total_addresses_help' => 'Number of addresses registered in the system.',
    'total_customers' => 'Total customers',
    'total_customers_help' => 'Number of customers registered in the system.',
    'total_designs' => 'Total designs',
    'total_designs_help' => 'Number of designs registered in the system.',
    'designs_today' => 'Designs added today',
    'designs_today_help' => 'Number of designs added today.',
  ],

  'charts' => [
    'users_growth_title'    => 'Users growth',
    'users_growth_subtitle' => 'Demo preview',
  ],

  'latest_users' => [
    'title'    => 'Latest users',
    'show_all' => 'Show all',
    'empty'    => 'No users yet.',
    'table'    => [
      'user'       => 'User',
      'email'      => 'Email',
      'role'       => 'Role',
      'created_at' => 'Created at',
    ],
  ],

  'system_info' => [
    'title'        => 'Quick system info',
    'laravel'      => 'Laravel version',
    'locale'       => 'Current locale',
    'current_user' => 'Current user',
    'today'        => 'Today',
    'total_admins' => 'Total admins',
    'total_design_options' => 'Total design options',
  ],

  'roles' => [
    'admin' => 'Admin',
    'customer' => 'User',
    'super_admin' => 'Super Admin',
  ]

];
