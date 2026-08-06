<?php

return [

    'title' => 'Invoices',

    'index' => [
        'title'       => 'Invoices',
        'heading'     => 'Invoices',
        'subheading'  => 'Manage and view all invoices.',
    ],

    'show' => [
        'title'                  => 'Invoice :number',
        'heading'                => 'Invoice :number',
        'subheading'             => 'Invoice details.',
        'back_to_list'           => 'Back to invoices list',
        'summary_title'          => 'Invoice summary',
        'invoice_number'         => 'Invoice Number',
        'order_id'               => 'Order ID',
        'customer'               => 'Customer',
        'total'                  => 'Total',
        'created_at'             => 'Created at',
        'items_title'            => 'Order items',
        'download_pdf'           => 'Download PDF',
    ],

    'filters' => [
        'search_label'           => 'Search',
        'search_placeholder'     => 'Search by invoice number or customer name',
        'submit'                 => 'Filter',
        'reset'                  => 'Reset',
    ],

    'table' => [
        'invoice_number'  => 'Invoice Number',
        'customer'        => 'Customer',
        'total'           => 'Total',
        'order_id'        => 'Order ID',
        'created_at'      => 'Created At',
        'actions'         => 'Actions',
        'view'            => 'View',
        'pdf'             => 'PDF',
        'empty'           => 'No invoices found.',
        'serial_number'   => 'Serial Number',
    ],

    'messages' => [
        'pdf_generated' => 'PDF generated successfully.',
    ],
];
