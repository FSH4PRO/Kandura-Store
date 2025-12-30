<?php

return [

  'title' => 'Orders',

  'index' => [
    'title'       => 'Orders',
    'heading'     => 'Orders',
    'subheading'  => 'Manage all customer orders.',
  ],

  'show' => [
    'title'                  => 'Order :id',
    'heading'                => 'Order #:id',
    'subheading'             => 'Order details and items.',
    'back_to_list'           => 'Back to orders list',
    'summary_title'          => 'Order summary',
    'order_id'               => 'Order ID',
    'customer'               => 'Customer',
    'total'                  => 'Total',
    'status'                 => 'Status',
    'created_at'             => 'Created at',
    'change_status_title'    => 'Change order status',
    'status_field'           => 'New status',
    'change_status_button'   => 'Update status',
    'items_title'            => 'Order items',
    'items_empty'            => 'No items in this order.',
  ],

  'filters' => [
    'search_label'           => 'Search',
    'search_placeholder'     => 'Search by order ID or customer name',
    'status_label'           => 'Status',
    'status_all'             => 'All statuses',
    'total_min'              => 'Min total',
    'total_max'              => 'Max total',
    'submit'                 => 'Filter',
    'reset'                  => 'Reset',
  ],

  'table' => [
    'customer'   => 'Customer',
    'total'      => 'Total',
    'status'     => 'Status',
    'created_at' => 'Created at',
    'actions'    => 'Actions',
    'view'       => 'View',
    'empty'      => 'No orders found.',
  ],

  'items' => [
    'design'      => 'Design',
    'size'        => 'Size',
    'quantity'    => 'Qty',
    'unit_price'  => 'Unit price',
    'total_price' => 'Total price',
    'options'     => 'Options',
  ],

  'statuses' => [
    'pending'   => 'Pending',
    'accepted'  => 'Accepted',
    'rejected'  => 'Rejected',
    'canceled'  => 'Canceled',
    'paid'      => 'Paid',
  ],

  'messages' => [
    'status_updated' => 'Order status updated successfully.',
  ],
];
