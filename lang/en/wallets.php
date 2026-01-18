<?php

return [
  'index' => [
    'title'       => 'Wallets',
    'heading'     => 'Wallets Management',
    'subheading'  => 'Manage customer wallets and balances.',
    'bulk_topup'  => 'Bulk Top-up',
  ],

  'show' => [
    'title'                => 'Wallet :id',
    'heading'              => 'Wallet',
    'subheading'           => 'Wallet details and transaction history.',
    'back'                 => 'Back to wallets',
    'wallet_info'          => 'Wallet Information',
    'status_active'        => 'Active',
    'status_inactive'      => 'Inactive',
    'customer'             => 'Customer',
    'balance'              => 'Balance',
    'created_at'           => 'Created at',
    'updated_at'           => 'Updated at',
    'add_credit'           => 'Add Credit',
    'deactivate'           => 'Deactivate',
    'activate'             => 'Activate',
    'transactions'         => 'Transactions',
    'transactions_count'   => 'transactions',
  ],

  'filters' => [
    'search_label'       => 'Search',
    'search_placeholder' => 'Search all columns...',
    'status_label'       => 'Status',
    'status_all'         => 'All statuses',
    'status_active'      => 'Active',
    'status_inactive'    => 'Inactive',
    'balance_min'        => 'Min balance',
    'balance_max'        => 'Max balance',
    'submit'             => 'Filter',
    'reset'              => 'Reset',
  ],

  'table' => [
    'customer'        => 'Customer',
    'balance'         => 'Balance',
    'status'          => 'Status',
    'created_at'      => 'Created at',
    'actions'         => 'Actions',
    'view'            => 'View',
    'add_credit'      => 'Add Credit',
    'deactivate'      => 'Deactivate',
    'activate'        => 'Activate',
    'status_active'   => 'Active',
    'status_inactive' => 'Inactive',
    'empty'           => 'No wallets found.',
  ],

  'transactions' => [
    'type'        => 'Type',
    'amount'      => 'Amount',
    'description' => 'Description',
    'date'        => 'Date',
    'credit'       => 'Credit',
    'debit'       => 'Debit',
    'empty'       => 'No transactions found.',
  ],

  'modal' => [
    'topup_title'      => 'Add Credit to Wallet',
    'bulk_topup_title' => 'Bulk Credit to All Wallets',
    'amount'           => 'Amount',
    'reference'        => 'Reference',
    'note'             => 'Note',
    'cancel'           => 'Cancel',
    'submit'           => 'Submit',
    'bulk_warning'     => 'This will add credit to all active wallets. This action cannot be undone.',
  ],

  'messages' => [
    'topped_up'           => 'Credit of :amount has been added to the wallet successfully.',
    'bulk_topped_up'      => 'Credit of :amount has been added to :count wallet(s) successfully.',
    'activated'           => 'Wallet has been activated successfully.',
    'deactivated'         => 'Wallet has been deactivated successfully.',
    'confirm_deactivate'  => 'Are you sure you want to deactivate this wallet? The customer will not be able to use it for payments.',
  ],
];
