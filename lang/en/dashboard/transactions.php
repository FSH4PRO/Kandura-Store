<?php

return [

  'title' => 'Transactions - Kandura Store',

  'detail' => [
    'title' => 'Transaction Details',
    'back_to_list' => 'Back to Transactions',
    'transaction_info' => 'Transaction Information',
    'customer_info' => 'Customer Information',
    'id' => 'Transaction ID',
    'type' => 'Type',
    'amount' => 'Amount',
    'status' => 'Status',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
    'description' => 'Description',
    'reference_id' => 'Reference ID',
    'wallet_balance' => 'Wallet Balance',
    'customer_since' => 'Customer Since',
  ],

  'filters' => [
    'title' => 'Filters',
    'type' => 'Transaction Type',
    'all_types' => 'All Types',
    'credit' => 'Credit',
    'debit' => 'Debit',
    'status' => 'Status',
    'all_statuses' => 'All Statuses',
    'pending' => 'Pending',
    'completed' => 'Completed',
    'failed' => 'Failed',
    'date_from' => 'Date From',
    'date_to' => 'Date To',
    'apply' => 'Apply Filters',
    'clear' => 'Clear Filters',
  ],

  'table' => [
    'id' => 'ID',
    'customer' => 'Customer',
    'type' => 'Type',
    'amount' => 'Amount',
    'status' => 'Status',
    'date' => 'Date',
    'actions' => 'Actions',
  ],

  'types' => [
    'credit' => 'Credit',
    'debit' => 'Debit',
  ],

  'statuses' => [
    'pending' => 'Pending',
    'completed' => 'Completed',
    'failed' => 'Failed',
  ],

  'actions' => [
    'view' => 'View',
  ],

  'validation' => [
    'type_invalid' => 'Invalid transaction type selected.',
    'status_invalid' => 'Invalid status selected.',
    'date_from_invalid' => 'Date from must be before or equal to date to.',
    'date_to_invalid' => 'Date to must be after or equal to date from.',
    'search_too_long' => 'Search query is too long.',
  ],

];
