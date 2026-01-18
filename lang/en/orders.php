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
    'subtotal'               => 'Subtotal',
    'discount'               => 'Discount',
    'coupon'                 => 'Coupon',
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
    'search_placeholder'     => 'Search all columns',
    'total_min'              => 'Min total',
    'total_max'              => 'Max total',
    'submit'                 => 'Filter',
    'reset'                  => 'Reset',
  ],

  'table' => [
    'customer'        => 'Customer',
    'total'           => 'Total',
    'discount'        => 'Discount',
    'status'          => 'Status',
    'items_count'     => 'Items',
    'payment_method'  => 'Payment Method',
    'payment_status'  => 'Payment Status',
    'created_at'      => 'Created at',
    'actions'         => 'Actions',
    'view'            => 'View',
    'empty'           => 'No orders found.',
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

  'payment_methods' => [
    'cod'    => 'Cash on Delivery',
    'stripe' => 'Credit Card',
    'wallet' => 'Wallet',
  ],

  'payment_statuses' => [
    'unpaid'   => 'Unpaid',
    'pending'  => 'Pending',
    'paid'     => 'Paid',
    'failed'   => 'Failed',
    'canceled' => 'Canceled',
  ],

  'messages' => [
    'status_updated' => 'Order status updated successfully.',
  ],

  'payment' => [
    'started' => 'Payment started successfully.',
  ],
];
